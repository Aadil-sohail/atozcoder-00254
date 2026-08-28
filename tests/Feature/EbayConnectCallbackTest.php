<?php

namespace Tests\Feature;

use App\Models\EbayAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * The store owner leaves the app for auth.ebay.com and comes back on a
 * cross-site redirect. These cover the ways that return trip can arrive.
 */
class EbayConnectCallbackTest extends TestCase
{
    use RefreshDatabase;

    private string $state = 'RmDkP2sVhYcXqL9tZwB6nJ4gA1eU8oIiTyF3rSxQ';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'ebay.sandbox' => true,
            'ebay.client_id' => 'test-client-id',
            'ebay.client_secret' => 'test-client-secret',
            'ebay.ru_name' => 'test-ru-name',
        ]);

    }

    /**
     * A cooperative eBay. Stubs passed in take precedence, so a test can make
     * one endpoint misbehave without restating the rest.
     */
    private function fakeEbay(array $overrides = []): void
    {
        Http::fake($overrides + [
            'api.sandbox.ebay.com/identity/v1/oauth2/token' => Http::response([
                'access_token' => 'test-access-token',
                'expires_in' => 7200,
                'refresh_token' => 'test-refresh-token',
                'refresh_token_expires_in' => 47304000,
            ]),
            'apiz.sandbox.ebay.com/commerce/identity/v1/user/' => Http::response([
                'username' => 'ldnautoparts',
            ]),
            '*' => Http::response([]),
        ]);
    }

    /**
     * Stand eBay up and park what connect() stores before redirecting the owner
     * to the consent page.
     */
    private function pendingConnect(array $ebayOverrides = []): void
    {
        $this->fakeEbay($ebayOverrides);

        Cache::put('ebay_oauth:'.$this->state, [
            'state' => $this->state,
            'store_name' => 'ldnautoparts',
            'marketplace_id' => 'EBAY_GB',
            'inserted_by' => 'Admin',
        ], now()->addMinutes(30));
    }

    /**
     * The regression this suite exists for: the callback used to sit behind the
     * "auth" middleware, so a return trip that arrived without the login cookie
     * was redirected to /login and the authorization code was thrown away
     * without a single line in the log.
     */
    public function test_store_is_connected_even_when_the_login_cookie_does_not_come_back(): void
    {
        $this->pendingConnect();

        $response = $this->get('/ebay/callback?code=v%5E1.1%23test-code&state='.$this->state);

        $this->assertDatabaseHas('ebay_accounts', [
            'store_name' => 'ldnautoparts',
            'ebay_username' => 'ldnautoparts',
            'marketplace_id' => 'EBAY_GB',
            'inserted_by' => 'Admin',
        ]);

        // No session to send them into, so they sign in and land on the store
        // they just connected rather than on a bare login screen.
        $response->assertRedirect(route('login'));
        $this->assertSame(
            route('ebay.setup', EbayAccount::first()),
            session('url.intended')
        );
    }

    public function test_tokens_are_stored_so_the_store_can_call_the_api(): void
    {
        $this->pendingConnect();

        $this->get('/ebay/callback?code=test-code&state='.$this->state);

        $account = EbayAccount::first();

        $this->assertSame('test-access-token', $account->access_token);
        $this->assertSame('test-refresh-token', $account->refresh_token);
        $this->assertTrue($account->hasValidAccessToken());
        $this->assertFalse($account->needsReconnect());
    }

    public function test_reconnecting_updates_the_existing_store_instead_of_duplicating_it(): void
    {
        EbayAccount::create([
            'store_name' => 'ldnautoparts',
            'ebay_username' => 'ldnautoparts',
            'marketplace_id' => 'EBAY_GB',
            'access_token' => 'stale-access-token',
            'access_token_expires_at' => now()->subDay(),
            'refresh_token' => 'stale-refresh-token',
            'refresh_token_expires_at' => now()->addYear(),
        ]);

        $this->pendingConnect();

        $this->get('/ebay/callback?code=test-code&state='.$this->state);

        $this->assertSame(1, EbayAccount::count());
        $this->assertSame('test-access-token', EbayAccount::first()->access_token);
    }

    public function test_a_connect_can_only_be_redeemed_once(): void
    {
        $this->pendingConnect();

        $this->get('/ebay/callback?code=test-code&state='.$this->state);
        $this->assertSame(1, EbayAccount::count());

        // Replaying the same callback URL must not connect a second store.
        $response = $this->get('/ebay/callback?code=test-code&state='.$this->state);

        $this->assertSame(1, EbayAccount::count());
        $response->assertRedirect(route('login'));
    }

    public function test_a_forged_state_connects_nothing(): void
    {
        $this->pendingConnect();

        $response = $this->get('/ebay/callback?code=test-code&state=not-the-real-state');

        $this->assertSame(0, EbayAccount::count());
        $response->assertRedirect(route('login'));
    }

    public function test_a_declined_authorization_connects_nothing(): void
    {
        $this->pendingConnect();

        $response = $this->get('/ebay/callback?error=access_denied&error_description=the+user+denied+access&state='.$this->state);

        $this->assertSame(0, EbayAccount::count());
        $response->assertRedirect(route('login'));
    }

    /**
     * The seller lookup is a nice-to-have; the tokens are the point.
     */
    public function test_store_is_still_connected_when_the_seller_lookup_fails(): void
    {
        $this->pendingConnect([
            'apiz.sandbox.ebay.com/*' => Http::response(['errors' => [['message' => 'Insufficient permissions']]], 403),
        ]);

        $this->get('/ebay/callback?code=test-code&state='.$this->state);

        $this->assertDatabaseHas('ebay_accounts', [
            'store_name' => 'ldnautoparts',
            'ebay_username' => null,
        ]);
    }
}
