<?php

namespace App\Http\Controllers;

use App\Models\EbayAccount;
use App\Services\EbayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        if (! config('ebay.client_id') || ! config('ebay.client_secret') || ! config('ebay.ru_name')) {
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

        return redirect()->away($this->ebay->authorizationUrl($state));
    }

    /**
     * OAuth callback: exchange the authorization code for tokens and save the store.
     */
    public function callback(Request $request): RedirectResponse
    {
        $pending = session()->pull('ebay_oauth');

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
        } catch (Throwable $e) {
            return redirect()->route('ebay.setup', $ebayAccount)->with('error', $e->getMessage());
        }

        return redirect()->route('ebay.setup', $ebayAccount)
            ->with('status', 'Default policies created and selected. Now set the ship-from location below and save.');
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

        // No existing location picked: a filled-in new-location form is required.
        if (! $locationKey) {
            $request->validate([
                'new_location.name' => ['required', 'string', 'max:100'],
                'new_location.address_line1' => ['required', 'string', 'max:180'],
                'new_location.city' => ['required', 'string', 'max:100'],
                'new_location.postal_code' => ['required', 'string', 'max:20'],
                'new_location.country' => ['required', 'string', 'size:2'],
            ], [
                'new_location.*.required' => 'Fill in the new ship-from location (or pick an existing one) — it is required before products can be published.',
            ]);

            try {
                $locationKey = $this->ebay->createInventoryLocation(
                    $ebayAccount,
                    $request->input('new_location'),
                    $request->input('new_location.name'),
                );
            } catch (RuntimeException $e) {
                return back()->withInput()->with('error', $e->getMessage());
            }
        }

        $ebayAccount->update([
            'fulfillment_policy_id' => $request->fulfillment_policy_id,
            'payment_policy_id' => $request->payment_policy_id,
            'return_policy_id' => $request->return_policy_id,
            'merchant_location_key' => $locationKey,
        ]);

        return redirect()->route('ebay.index')->with('status', "Store \"{$ebayAccount->store_name}\" is ready to sync products.");
    }

    /**
     * Disconnect a store (its listing links are removed with it).
     */
    public function destroy(EbayAccount $ebayAccount): RedirectResponse
    {
        $ebayAccount->delete();

        return redirect()->route('ebay.index')->with('status', 'eBay store disconnected.');
    }

    /**
     * Shared callback handling.
     */
    private function handleCallback(Request $request, ?array $pending): RedirectResponse
    {
        if (! $pending || $request->state !== $pending['state']) {
            return redirect()->route('ebay.index')->with('error', 'The eBay authorization session expired or was invalid. Please try connecting again.');
        }

        if ($request->filled('error') || ! $request->filled('code')) {
            return redirect()->route('ebay.index')->with('warning', 'eBay authorization was declined.');
        }

        try {
            $tokens = $this->ebay->exchangeCode($request->code);
        } catch (RuntimeException $e) {
            return redirect()->route('ebay.index')->with('error', $e->getMessage());
        }

        $account = EbayAccount::create([
            'store_name' => $pending['store_name'],
            'marketplace_id' => $pending['marketplace_id'],
            'access_token' => $tokens['access_token'],
            'access_token_expires_at' => now()->addSeconds((int) ($tokens['expires_in'] ?? 7200)),
            'refresh_token' => $tokens['refresh_token'],
            'refresh_token_expires_at' => now()->addSeconds((int) ($tokens['refresh_token_expires_in'] ?? 47304000)),
            'inserted_by' => auth()->user()->name,
        ]);

        // Best effort: identify the seller and pre-select policies/location.
        try {
            $account->ebay_username = $this->ebay->fetchUsername($account);

            $policies = $this->ebay->fetchPolicies($account);
            $account->fulfillment_policy_id = $policies['fulfillment'][0]['fulfillmentPolicyId'] ?? null;
            $account->payment_policy_id = $policies['payment'][0]['paymentPolicyId'] ?? null;
            $account->return_policy_id = $policies['return'][0]['returnPolicyId'] ?? null;

            $locations = $this->ebay->fetchInventoryLocations($account);
            $account->merchant_location_key = $locations[0]['merchantLocationKey'] ?? null;

            $account->save();
        } catch (Throwable) {
            // Setup page will let the user finish configuration manually.
        }

        if (! $account->isFullyConfigured()) {
            return redirect()->route('ebay.setup', $account)
                ->with('info', 'Store connected. Finish the setup below so products can be published.');
        }

        return redirect()->route('ebay.index')->with('status', "eBay store \"{$account->store_name}\" connected successfully.");
    }
}
