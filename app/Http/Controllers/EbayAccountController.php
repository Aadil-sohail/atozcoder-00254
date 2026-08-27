<?php

namespace App\Http\Controllers;

use App\Models\EbayAccount;
use App\Services\EbayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class EbayAccountController extends Controller
{
    public function __construct(private EbayService $ebay)
    {
        $this->middleware('permission:view ebay stores')->only('index');
        $this->middleware('permission:create ebay stores')->only(['connect', 'callback']);
        $this->middleware('permission:edit ebay stores')->only(['setup', 'saveSetup', 'createDefaultPolicies']);
        $this->middleware('permission:delete ebay stores')->only('destroy');
    }

    /**
     * List the connected eBay stores.
     */
    public function index(): View
    {
        $accounts = EbayAccount::withCount('listings')->orderBy('store_name')->get();
        $credentialsMissing = ! config('ebay.client_id') || ! config('ebay.client_secret') || ! config('ebay.ru_name');

        return view('ebay.index', compact('accounts', 'credentialsMissing'));
    }

    /**
     * Send the user to eBay's consent page to authorize a store.
     */
    public function connect(Request $request): RedirectResponse
    {
        $request->validate([
            'store_name' => ['required', 'string', 'max:100'],
            'marketplace_id' => ['required', 'string', 'in:'.implode(',', array_keys(config('ebay.marketplaces')))],
        ]);

        Log::info('eBay connect: store owner started connecting a store', [
            'store_name' => $request->store_name,
            'marketplace_id' => $request->marketplace_id,
            'environment' => config('ebay.sandbox') ? 'sandbox' : 'production',
            'app_user' => auth()->user()?->name,
            'callback_url' => route('ebay.callback'),
        ]);

        if (! config('ebay.client_id') || ! config('ebay.client_secret') || ! config('ebay.ru_name')) {
            Log::error('eBay connect: aborted, credentials are missing from the environment', [
                'has_client_id' => (bool) config('ebay.client_id'),
                'has_client_secret' => (bool) config('ebay.client_secret'),
                'has_ru_name' => (bool) config('ebay.ru_name'),
            ]);

            return redirect()->route('ebay.index')
                ->with('error', 'eBay credentials are not configured. Add EBAY_CLIENT_ID, EBAY_CLIENT_SECRET and EBAY_RU_NAME to your .env file.');
        }

        $state = Str::random(40);

        session([
            'ebay_oauth' => [
                'state' => $state,
                'store_name' => $request->store_name,
                'marketplace_id' => $request->marketplace_id,
            ],
        ]);

        $url = $this->ebay->authorizationUrl($state);

        Log::info('eBay connect: redirecting the store owner to eBay for consent', [
            'store_name' => $request->store_name,
            'state' => $state,
            'session_id' => session()->getId(),
        ]);

        return redirect()->away($url);
    }

    /**
     * OAuth callback: exchange the authorization code for tokens and save the store.
     */
    public function callback(Request $request): RedirectResponse
    {
        $pending = session()->pull('ebay_oauth');

        Log::info('eBay connect: callback received from eBay', [
            'has_code' => $request->filled('code'),
            'code' => EbayService::mask($request->query('code')),
            'state' => $request->query('state'),
            'error' => $request->query('error'),
            'error_description' => $request->query('error_description'),
            'pending_session' => $pending ? [
                'store_name' => $pending['store_name'],
                'marketplace_id' => $pending['marketplace_id'],
                'state' => $pending['state'],
            ] : null,
            'session_id' => session()->getId(),
        ]);

        return $this->handleCallback($request, $pending);
    }

    /**
     * Show the setup page: pick business policies and an inventory location.
     */
    public function setup(EbayAccount $ebayAccount): View
    {
        $policies = ['fulfillment' => [], 'payment' => [], 'return' => []];
        $locations = [];
        $loadError = null;

        try {
            $policies = $this->ebay->fetchPolicies($ebayAccount);
            $locations = $this->ebay->fetchInventoryLocations($ebayAccount);

            // No policies at all usually means the seller isn't opted in yet.
            if ($policies['fulfillment'] === [] && $policies['payment'] === [] && $policies['return'] === []) {
                $this->ebay->optInToBusinessPolicies($ebayAccount);
                $policies = $this->ebay->fetchPolicies($ebayAccount);
            }
        } catch (Throwable $e) {
            $loadError = $e->getMessage();

            Log::error('eBay connect: could not load policies/locations for store "'.$ebayAccount->store_name."\" (#{$ebayAccount->id})", [
                'exception' => $e->getMessage(),
            ]);
        }

        return view('ebay.setup', compact('ebayAccount', 'policies', 'locations', 'loadError'));
    }

    /**
     * Create a basic set of business policies on the seller account via the API.
     */
    public function createDefaultPolicies(EbayAccount $ebayAccount): RedirectResponse
    {
        try {
            $this->ebay->createDefaultPolicies($ebayAccount);

            // Also give the store a ship-from location so one click completes the
            // whole setup. Reuse an existing eBay location if there already is one,
            // otherwise create one from the default address in config/ebay.php.
            if (! $ebayAccount->merchant_location_key) {
                $locations = $this->ebay->fetchInventoryLocations($ebayAccount);

                if ($locations !== []) {
                    $ebayAccount->merchant_location_key = $locations[0]['merchantLocationKey'];
                } else {
                    $address = config('ebay.default_location');
                    $ebayAccount->merchant_location_key = $this->ebay->createInventoryLocation(
                        $ebayAccount, $address, $address['name']
                    );
                }

                $ebayAccount->save();
            }
        } catch (Throwable $e) {
            return redirect()->route('ebay.setup', $ebayAccount)->with('error', $e->getMessage());
        }

        return redirect()->route('ebay.setup', $ebayAccount)
            ->with('status', 'Default policies and a ship-from location were set up. Review them below, then click Save Setup.');
    }

    /**
     * Persist the chosen policies / location for the store.
     */
    public function saveSetup(Request $request, EbayAccount $ebayAccount): RedirectResponse
    {
        $request->validate([
            'fulfillment_policy_id' => ['required', 'string', 'max:50'],
            'payment_policy_id' => ['required', 'string', 'max:50'],
            'return_policy_id' => ['required', 'string', 'max:50'],
            'merchant_location_key' => ['nullable', 'string', 'max:50'],
            'new_location.name' => ['nullable', 'string', 'max:100'],
            'new_location.address_line1' => ['nullable', 'string', 'max:180'],
            'new_location.city' => ['nullable', 'string', 'max:100'],
            'new_location.state' => ['nullable', 'string', 'max:100'],
            'new_location.postal_code' => ['nullable', 'string', 'max:20'],
            'new_location.country' => ['nullable', 'string', 'size:2'],
        ]);

        $locationKey = $request->merchant_location_key;
        $address = null;
        $usedDefaultLocation = false;

        // No existing location picked: figure out where to ship from without
        // dead-ending on a required form.
        if (! $locationKey) {
            // Treat the new-location form as "in use" only once a real address
            // has been typed (Country defaults to US, so it never counts alone).
            $formFilled = filled($request->input('new_location.address_line1'))
                || filled($request->input('new_location.city'))
                || filled($request->input('new_location.postal_code'));

            if ($formFilled) {
                // The user started an address: require the whole set.
                $request->validate([
                    'new_location.name' => ['required', 'string', 'max:100'],
                    'new_location.address_line1' => ['required', 'string', 'max:180'],
                    'new_location.city' => ['required', 'string', 'max:100'],
                    'new_location.postal_code' => ['required', 'string', 'max:20'],
                    'new_location.country' => ['required', 'string', 'size:2'],
                ], [
                    'new_location.*.required' => 'Fill in every ship-from location field, or clear them all to use a default location automatically.',
                ]);

                $address = $request->input('new_location');
            } else {
                // Nothing entered: reuse an existing eBay location, otherwise
                // create one from the default address so Save always completes.
                $existing = $this->ebay->fetchInventoryLocations($ebayAccount);

                if ($existing !== []) {
                    $locationKey = $existing[0]['merchantLocationKey'];
                } else {
                    $address = config('ebay.default_location');
                    $usedDefaultLocation = true;
                }
            }

            if (! $locationKey) {
                try {
                    $locationKey = $this->ebay->createInventoryLocation($ebayAccount, $address, $address['name']);
                } catch (RuntimeException $e) {
                    return back()->withInput()->with('error', $e->getMessage());
                }
            }
        }

        $ebayAccount->update([
            'fulfillment_policy_id' => $request->fulfillment_policy_id,
            'payment_policy_id' => $request->payment_policy_id,
            'return_policy_id' => $request->return_policy_id,
            'merchant_location_key' => $locationKey,
        ]);

        $message = "Store \"{$ebayAccount->store_name}\" is ready to sync products.";

        if ($usedDefaultLocation) {
            $message .= ' A default ship-from address was used — update it later if it is not correct.';
        }

        return redirect()->route('ebay.index')->with('status', $message);
    }

    /**
     * Disconnect a store (its listing links are removed with it).
     */
    public function destroy(EbayAccount $ebayAccount): RedirectResponse
    {
        Log::info('eBay connect: store "'.$ebayAccount->store_name."\" (#{$ebayAccount->id}) disconnected by ".(auth()->user()?->name ?? 'unknown user'));

        $ebayAccount->delete();

        return redirect()->route('ebay.index')->with('status', 'eBay store disconnected.');
    }

    /**
     * Shared callback handling.
     */
    private function handleCallback(Request $request, ?array $pending): RedirectResponse
    {
        if (! $pending || $request->state !== $pending['state']) {
            Log::warning('eBay connect: callback rejected, the authorization session expired or the state did not match', [
                'had_pending_session' => (bool) $pending,
                'state_returned' => $request->state,
                'state_expected' => $pending['state'] ?? null,
            ]);

            return redirect()->route('ebay.index')->with('error', 'The eBay authorization session expired or was invalid. Please try connecting again.');
        }

        if ($request->filled('error') || ! $request->filled('code')) {
            Log::warning('eBay connect: authorization was declined or returned no code', [
                'error' => $request->query('error'),
                'error_description' => $request->query('error_description'),
            ]);

            return redirect()->route('ebay.index')->with('warning', 'eBay authorization was declined.');
        }

        try {
            $tokens = $this->ebay->exchangeCode($request->code);
        } catch (RuntimeException $e) {
            Log::error('eBay connect: token exchange failed, store not connected', [
                'store_name' => $pending['store_name'],
                'exception' => $e->getMessage(),
            ]);

            return redirect()->route('ebay.index')->with('error', $e->getMessage());
        }

        $tokenFields = [
            'access_token' => $tokens['access_token'],
            'access_token_expires_at' => now()->addSeconds((int) ($tokens['expires_in'] ?? 7200)),
            'refresh_token' => $tokens['refresh_token'],
            'refresh_token_expires_at' => now()->addSeconds((int) ($tokens['refresh_token_expires_in'] ?? 47304000)),
        ];

        // Identify the seller with the fresh token so re-connecting updates
        // the existing store row instead of creating a duplicate.
        $username = $this->ebay->fetchUsername(new EbayAccount($tokenFields + ['store_name' => $pending['store_name']]));

        $account = EbayAccount::where('marketplace_id', $pending['marketplace_id'])
            ->where(function ($query) use ($username, $pending) {
                $query->where('store_name', $pending['store_name']);

                if ($username) {
                    $query->orWhere('ebay_username', $username);
                }
            })
            ->first();

        $reconnected = $account !== null;

        if ($reconnected) {
            $account->update($tokenFields + ['store_name' => $pending['store_name']]);
        } else {
            $account = EbayAccount::create($tokenFields + [
                'store_name' => $pending['store_name'],
                'marketplace_id' => $pending['marketplace_id'],
                'inserted_by' => auth()->user()->name,
            ]);
        }

        Log::info('eBay connect: store '.($reconnected ? 're-connected' : 'connected').' and tokens saved', [
            'account_id' => $account->id,
            'store_name' => $account->store_name,
            'marketplace_id' => $account->marketplace_id,
            'ebay_username' => $username,
            'access_token_expires_at' => (string) $tokenFields['access_token_expires_at'],
            'refresh_token_expires_at' => (string) $tokenFields['refresh_token_expires_at'],
        ]);

        // Best effort: identify the seller and pre-select policies/location
        // (never overwriting choices already made on the setup page).
        try {
            $account->ebay_username = $username ?: $account->ebay_username;

            if (! $account->isFullyConfigured()) {
                $policies = $this->ebay->fetchPolicies($account);
                $account->fulfillment_policy_id = $account->fulfillment_policy_id ?? ($policies['fulfillment'][0]['fulfillmentPolicyId'] ?? null);
                $account->payment_policy_id = $account->payment_policy_id ?? ($policies['payment'][0]['paymentPolicyId'] ?? null);
                $account->return_policy_id = $account->return_policy_id ?? ($policies['return'][0]['returnPolicyId'] ?? null);

                $locations = $this->ebay->fetchInventoryLocations($account);
                $account->merchant_location_key = $account->merchant_location_key ?? ($locations[0]['merchantLocationKey'] ?? null);
            }

            $account->save();

            Log::info("eBay connect: setup pre-filled for store #{$account->id}", [
                'fulfillment_policy_id' => $account->fulfillment_policy_id,
                'payment_policy_id' => $account->payment_policy_id,
                'return_policy_id' => $account->return_policy_id,
                'merchant_location_key' => $account->merchant_location_key,
                'fully_configured' => $account->isFullyConfigured(),
            ]);
        } catch (Throwable $e) {
            // Setup page will let the user finish configuration manually.
            Log::warning("eBay connect: could not pre-fill policies/location for store #{$account->id}, the setup page will ask for them", [
                'exception' => $e->getMessage(),
            ]);
        }

        if (! $account->isFullyConfigured()) {
            Log::info("eBay connect: store #{$account->id} needs manual setup before products can be published");

            return redirect()->route('ebay.setup', $account)
                ->with('info', 'Store connected. Finish the setup below so products can be published.');
        }

        Log::info('eBay connect: store #'.$account->id.' "'.$account->store_name.'" is fully configured and ready to sync');

        return redirect()->route('ebay.index')->with('status', $reconnected
            ? "eBay store \"{$account->store_name}\" re-connected — permissions refreshed."
            : "eBay store \"{$account->store_name}\" connected successfully.");
    }
}
