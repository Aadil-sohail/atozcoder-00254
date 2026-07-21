<?php

namespace App\Services;

use RuntimeException;
use App\Models\EbayAccount;
use App\Models\EbayListing;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\PendingRequest;

class EbayService
{
    /*
    |--------------------------------------------------------------------------
    | Environment URLs
    |--------------------------------------------------------------------------
    */

    public function authBase(): string
    {
        return config('ebay.sandbox')
            ? 'https://auth.sandbox.ebay.com'
            : 'https://auth.ebay.com';
    }

    public function apiBase(): string
    {
        return config('ebay.sandbox')
            ? 'https://api.sandbox.ebay.com'
            : 'https://api.ebay.com';
    }

    /*
    |--------------------------------------------------------------------------
    | OAuth (connecting a store)
    |--------------------------------------------------------------------------
    */

    /**
     * URL of eBay's consent page where the store owner logs in and approves access.
     */
    public function authorizationUrl(string $state): string
    {
        return $this->authBase().'/oauth2/authorize?'.http_build_query([
            'client_id' => config('ebay.client_id'),
            'redirect_uri' => config('ebay.ru_name'),
            'response_type' => 'code',
            'scope' => implode(' ', config('ebay.scopes')),
            'state' => $state,
        ]);
    }

    /**
     * Exchange the authorization code returned by eBay for access + refresh tokens.
     *
     * @return array{access_token: string, expires_in: int, refresh_token: string, refresh_token_expires_in: int}
     */
    public function exchangeCode(string $code): array
    {
        $response = Http::asForm()
            ->withBasicAuth(config('ebay.client_id'), config('ebay.client_secret'))
            ->post($this->apiBase().'/identity/v1/oauth2/token', [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => config('ebay.ru_name'),
            ]);

        if ($response->failed()) {
            $this->logFailure('authorization code exchange failed', $response);
            throw new RuntimeException('eBay token exchange failed: '.$this->errorMessage($response));
        }

        Log::info('eBay: authorization code exchanged for tokens successfully');

        return $response->json();
    }

    /**
     * Return a usable access token for the account, refreshing it via the
     * stored refresh token when the cached one is expired (2 hour lifetime).
     */
    public function ensureAccessToken(EbayAccount $account): string
    {
        if ($account->hasValidAccessToken()) {
            return $account->access_token;
        }

        if ($account->needsReconnect()) {
            Log::warning("eBay: refresh token expired for store \"{$account->store_name}\" (#{$account->id}), re-connect required");
            throw new RuntimeException("The eBay authorization for \"{$account->store_name}\" has expired. Please re-connect the store.");
        }

        Log::info("eBay: refreshing access token for store \"{$account->store_name}\" (#{$account->id})");

        // No scope parameter: eBay then re-issues the scopes originally granted,
        // so adding new scopes to the config never breaks existing connections.
        $response = Http::asForm()
            ->withBasicAuth(config('ebay.client_id'), config('ebay.client_secret'))
            ->post($this->apiBase().'/identity/v1/oauth2/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $account->refresh_token,
            ]);

        if ($response->failed()) {
            $this->logFailure("access token refresh failed for store #{$account->id}", $response);
            throw new RuntimeException('eBay token refresh failed: '.$this->errorMessage($response));
        }

        $account->update([
            'access_token' => $response->json('access_token'),
            'access_token_expires_at' => now()->addSeconds((int) $response->json('expires_in', 7200)),
        ]);

        return $account->access_token;
    }

    /**
     * Application token (client credentials) used for public data such as
     * the Taxonomy API. Cached until shortly before it expires.
     */
    public function appToken(): string
    {
        return Cache::remember('ebay.app_token', 6600, function () {
            $response = Http::asForm()
                ->withBasicAuth(config('ebay.client_id'), config('ebay.client_secret'))
                ->post($this->apiBase().'/identity/v1/oauth2/token', [
                    'grant_type' => 'client_credentials',
                    'scope' => 'https://api.ebay.com/oauth/api_scope',
                ]);

            if ($response->failed()) {
                throw new RuntimeException('eBay app token request failed: '.$this->errorMessage($response));
            }

            return $response->json('access_token');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Account data (username, policies, locations)
    |--------------------------------------------------------------------------
    */

    /**
     * eBay username of the connected seller (Commerce Identity API).
     */
    public function fetchUsername(EbayAccount $account): ?string
    {
        $response = $this->api($account)->get('/commerce/identity/v1/user/');

        return $response->successful() ? $response->json('username') : null;
    }

    /**
     * Fulfillment, payment and return business policies for the account's
     * marketplace. All three are required before an offer can be published.
     *
     * @return array{fulfillment: array, payment: array, return: array}
     */
    public function fetchPolicies(EbayAccount $account): array
    {
        $marketplace = ['marketplace_id' => $account->marketplace_id];

        return [
            'fulfillment' => $this->api($account)->get('/sell/account/v1/fulfillment_policy', $marketplace)
                ->json('fulfillmentPolicies', []),
            'payment' => $this->api($account)->get('/sell/account/v1/payment_policy', $marketplace)
                ->json('paymentPolicies', []),
            'return' => $this->api($account)->get('/sell/account/v1/return_policy', $marketplace)
                ->json('returnPolicies', []),
        ];
    }

    /**
     * Opt the seller in to business policies (no-op if already opted in).
     */
    public function optInToBusinessPolicies(EbayAccount $account): void
    {
        $this->api($account)->post('/sell/account/v1/program/opt_in', [
            'programType' => 'SELLING_POLICY_MANAGEMENT',
        ]);
    }

    /**
     * Candidate flat-rate shipping services per marketplace, used when creating
     * a basic fulfillment policy automatically. Codes come from eBay's shipping
     * service tokens; the first one the account accepts is used.
     */
    private const DEFAULT_SHIPPING_SERVICES = [
        'EBAY_US' => ['carrier' => 'USPS', 'services' => ['USPSPriority', 'USPSGroundAdvantage', 'USPSFirstClass', 'USPSParcel']],
        'EBAY_GB' => ['carrier' => 'RoyalMail', 'services' => ['UK_RoyalMailSecondClassStandard', 'UK_RoyalMailFirstClassStandard']],
        'EBAY_CA' => ['carrier' => 'CanadaPost', 'services' => ['CA_RegularParcel', 'CA_ExpeditedParcel']],
        'EBAY_AU' => ['carrier' => 'AustraliaPost', 'services' => ['AU_Regular', 'AU_StandardDelivery']],
        'EBAY_DE' => ['carrier' => 'DHL', 'services' => ['DE_DHLPaket', 'DE_DeutschePostBrief']],
    ];

    /**
     * Create a basic set of business policies (free domestic shipping, managed
     * payments, 30-day returns) and store their ids on the account.
     */
    public function createDefaultPolicies(EbayAccount $account): void
    {
        $this->optInToBusinessPolicies($account);

        $categoryTypes = [['name' => 'ALL_EXCLUDING_MOTORS_VEHICLES']];
        $shipping = self::DEFAULT_SHIPPING_SERVICES[$account->marketplace_id] ?? self::DEFAULT_SHIPPING_SERVICES['EBAY_US'];

        if (! $account->fulfillment_policy_id) {
            $response = null;

            foreach ($shipping['services'] as $serviceCode) {
                $response = $this->api($account)->post('/sell/account/v1/fulfillment_policy', [
                    'name' => 'Default Shipping',
                    'marketplaceId' => $account->marketplace_id,
                    'categoryTypes' => $categoryTypes,
                    'handlingTime' => ['value' => 1, 'unit' => 'DAY'],
                    'shippingOptions' => [[
                        'costType' => 'FLAT_RATE',
                        'optionType' => 'DOMESTIC',
                        'shippingServices' => [[
                            'sortOrder' => 1,
                            'shippingCarrierCode' => $shipping['carrier'],
                            'shippingServiceCode' => $serviceCode,
                            'freeShipping' => true,
                        ]],
                    ]],
                ]);

                if ($response->successful()) {
                    Log::info("eBay: fulfillment policy created with shipping service \"{$serviceCode}\"", ['policy_id' => $response->json('fulfillmentPolicyId')]);
                    break;
                }

                Log::warning("eBay: shipping service \"{$serviceCode}\" rejected, trying next candidate", ['status' => $response->status()]);
            }

            if (! $response || $response->failed()) {
                $this->logFailure('all shipping service candidates rejected, fulfillment policy not created', $response);
                throw new RuntimeException('Could not create shipping policy: '.$this->errorMessage($response));
            }

            $account->fulfillment_policy_id = $response->json('fulfillmentPolicyId');
        }

        if (! $account->payment_policy_id) {
            $response = $this->api($account)->post('/sell/account/v1/payment_policy', [
                'name' => 'Default Payments',
                'marketplaceId' => $account->marketplace_id,
                'categoryTypes' => $categoryTypes,
            ]);

            if ($response->failed()) {
                $this->logFailure('payment policy creation failed', $response);
                throw new RuntimeException('Could not create payment policy: '.$this->errorMessage($response));
            }

            Log::info('eBay: payment policy created', ['policy_id' => $response->json('paymentPolicyId')]);

            $account->payment_policy_id = $response->json('paymentPolicyId');
        }

        if (! $account->return_policy_id) {
            $response = $this->api($account)->post('/sell/account/v1/return_policy', [
                'name' => 'Default Returns',
                'marketplaceId' => $account->marketplace_id,
                'categoryTypes' => $categoryTypes,
                'returnsAccepted' => true,
                'returnPeriod' => ['value' => 30, 'unit' => 'DAY'],
                'returnShippingCostPayer' => 'BUYER',
            ]);

            if ($response->failed()) {
                $this->logFailure('return policy creation failed', $response);
                throw new RuntimeException('Could not create return policy: '.$this->errorMessage($response));
            }

            Log::info('eBay: return policy created', ['policy_id' => $response->json('returnPolicyId')]);

            $account->return_policy_id = $response->json('returnPolicyId');
        }

        $account->save();
    }

    /**
     * Existing inventory locations (ship-from addresses) for the account.
     */
    public function fetchInventoryLocations(EbayAccount $account): array
    {
        $response = $this->api($account)->get('/sell/inventory/v1/location', ['limit' => 100]);

        return $response->successful() ? $response->json('locations', []) : [];
    }

    /**
     * Create an inventory location (ship-from address) and return its key.
     */
    public function createInventoryLocation(EbayAccount $account, array $address, string $name): string
    {
        $key = Str::slug(Str::limit($name, 28, ''), '-').'-'.$account->id;

        $response = $this->api($account)->post('/sell/inventory/v1/location/'.$key, [
            'location' => [
                'address' => array_filter([
                    'addressLine1' => $address['address_line1'],
                    'city' => $address['city'],
                    'stateOrProvince' => $address['state'] ?? null,
                    'postalCode' => $address['postal_code'],
                    'country' => strtoupper($address['country']),
                ]),
            ],
            'name' => $name,
            'merchantLocationStatus' => 'ENABLED',
            'locationTypes' => ['WAREHOUSE'],
        ]);

        if ($response->failed()) {
            $this->logFailure("inventory location \"{$key}\" creation failed", $response);
            throw new RuntimeException('Could not create eBay inventory location: '.$this->errorMessage($response));
        }

        Log::info("eBay: inventory location \"{$key}\" created for store #{$account->id}");

        return $key;
    }

    /*
    |--------------------------------------------------------------------------
    | Category suggestion (Taxonomy API)
    |--------------------------------------------------------------------------
    */

    /**
     * Suggest the best matching eBay leaf category for a product title.
     */
    public function suggestCategoryId(EbayAccount $account, string $query): ?string
    {
        $http = Http::withToken($this->appToken())->baseUrl($this->apiBase());

        $treeId = $http->get('/commerce/taxonomy/v1/get_default_category_tree_id', [
            'marketplace_id' => $account->marketplace_id,
        ])->json('categoryTreeId');

        if (! $treeId) {
            return null;
        }

        return $http->get("/commerce/taxonomy/v1/category_tree/{$treeId}/get_category_suggestions", [
            'q' => Str::limit($query, 80, ''),
        ])->json('categorySuggestions.0.category.categoryId');
    }

    /*
    |--------------------------------------------------------------------------
    | Product sync (inventory item -> offer -> publish)
    |--------------------------------------------------------------------------
    */

    /**
     * Push a product to eBay as a live listing. Throws on failure.
     */
    public function syncListing(EbayListing $listing): void
    {
        $account = $listing->ebayAccount;
        $product = $listing->product;

        Log::info("eBay: sync started for product #{$product->id} \"{$product->name}\" (SKU {$listing->sku}) to store \"{$account->store_name}\"");

        if (! $account->isFullyConfigured()) {
            Log::warning("eBay: sync aborted, store \"{$account->store_name}\" is not fully configured");
            throw new RuntimeException("Store \"{$account->store_name}\" is missing business policies or an inventory location. Open its Setup page first.");
        }

        $marketplace = config("ebay.marketplaces.{$account->marketplace_id}", config('ebay.marketplaces.EBAY_US'));
        $quantity = max(0, (int) round($product->total_qty - $product->sold_qty));

        // Step 1: create/replace the inventory item record (keyed by SKU).
        $item = [
            'availability' => [
                'shipToLocationAvailability' => ['quantity' => $quantity],
            ],
            'condition' => $listing->condition,
            'product' => array_filter([
                'title' => Str::limit($product->name, 80, ''),
                'description' => $product->description ?: $product->name,
                'imageUrls' => $this->imageUrls($product->image),
                // Brand/Type cover the item specifics most categories require.
                'aspects' => array_filter([
                    'Brand' => ['Unbranded'],
                    'Type' => [$product->category->name ?? 'General'],
                    'Size' => $product->size ? [$product->size] : null,
                ]),
            ]),
        ];

        $response = $this->api($account)
            ->withHeaders(['Content-Language' => $marketplace['language']])
            ->put('/sell/inventory/v1/inventory_item/'.rawurlencode($listing->sku), $item);

        if ($response->failed()) {
            $this->logFailure("inventory item PUT failed for SKU {$listing->sku}", $response);
            throw new RuntimeException('Inventory item failed: '.$this->errorMessage($response));
        }

        Log::info("eBay: inventory item created/updated for SKU {$listing->sku} (quantity {$quantity})");

        // Step 2: resolve the eBay category if none was chosen. Try the product
        // title, then the internal category name, then the configured fallback.
        if (! $listing->ebay_category_id) {
            $listing->ebay_category_id = $this->suggestCategoryId($account, $product->name)
                ?? ($product->category ? $this->suggestCategoryId($account, $product->category->name) : null)
                ?? config('ebay.fallback_category_id');

            if (! $listing->ebay_category_id) {
                Log::warning("eBay: no category suggestion found for \"{$product->name}\" and no fallback configured");
                throw new RuntimeException('No eBay category could be suggested for this product. Enter a category ID in the sync popup, or set EBAY_FALLBACK_CATEGORY_ID in .env.');
            }

            Log::info("eBay: category {$listing->ebay_category_id} resolved for \"{$product->name}\"");

            $listing->save();
        }

        // Step 3: create or update the offer.
        $offer = [
            'availableQuantity' => $quantity,
            'categoryId' => $listing->ebay_category_id,
            'listingDescription' => $product->description ?: $product->name,
            'listingPolicies' => [
                'fulfillmentPolicyId' => $account->fulfillment_policy_id,
                'paymentPolicyId' => $account->payment_policy_id,
                'returnPolicyId' => $account->return_policy_id,
            ],
            'pricingSummary' => [
                'price' => [
                    'value' => number_format((float) $product->selling_price, 2, '.', ''),
                    'currency' => $marketplace['currency'],
                ],
            ],
            'merchantLocationKey' => $account->merchant_location_key,
        ];

        if ($listing->offer_id) {
            $response = $this->api($account)
                ->withHeaders(['Content-Language' => $marketplace['language']])
                ->put('/sell/inventory/v1/offer/'.$listing->offer_id, $offer);

            if ($response->failed()) {
                $this->logFailure("offer {$listing->offer_id} update failed", $response);
                throw new RuntimeException('Offer update failed: '.$this->errorMessage($response));
            }

            Log::info("eBay: offer {$listing->offer_id} updated");
        } else {
            $response = $this->api($account)
                ->withHeaders(['Content-Language' => $marketplace['language']])
                ->post('/sell/inventory/v1/offer', $offer + [
                    'sku' => $listing->sku,
                    'marketplaceId' => $account->marketplace_id,
                    'format' => 'FIXED_PRICE',
                ]);

            if ($response->successful()) {
                $listing->offer_id = $response->json('offerId');
                Log::info("eBay: offer {$listing->offer_id} created for SKU {$listing->sku}");
            } elseif ($this->hasErrorId($response, 25002)) {
                // An offer already exists for this SKU + marketplace: recover its id.
                $listing->offer_id = $this->findOfferId($account, $listing->sku);
                Log::info("eBay: recovered existing offer {$listing->offer_id} for SKU {$listing->sku}");
            }

            if (! $listing->offer_id) {
                $this->logFailure("offer creation failed for SKU {$listing->sku}", $response);
                throw new RuntimeException('Offer creation failed: '.$this->errorMessage($response));
            }

            $listing->save();
        }

        // Already live: updating the inventory item and offer refreshes the
        // listing directly; publishing again would be rejected by eBay.
        if ($listing->listing_id) {
            $listing->update([
                'sync_status' => 'synced',
                'last_error' => null,
                'last_synced_at' => now(),
            ]);

            Log::info("eBay: live listing {$listing->listing_id} refreshed for SKU {$listing->sku}");

            return;
        }

        // Step 4: publish the offer -> live eBay listing.
        $response = $this->api($account)->post("/sell/inventory/v1/offer/{$listing->offer_id}/publish");

        if ($response->failed()) {
            $this->logFailure("publish failed for offer {$listing->offer_id} (SKU {$listing->sku})", $response);
            throw new RuntimeException('Publish failed: '.$this->errorMessage($response));
        }

        $listing->update([
            'listing_id' => $response->json('listingId'),
            'sync_status' => 'synced',
            'last_error' => null,
            'last_synced_at' => now(),
        ]);

        Log::info("eBay: product #{$product->id} published as eBay listing {$listing->listing_id} on \"{$account->store_name}\"");
    }

    /**
     * Remove a product from eBay: ends the live listing and deletes the
     * offer + inventory item record on the seller account.
     */
    public function endListing(EbayListing $listing): void
    {
        $account = $listing->ebayAccount;

        $response = $this->api($account)->delete('/sell/inventory/v1/inventory_item/'.rawurlencode($listing->sku));

        // 404 means the SKU is already gone on eBay's side - treat as removed.
        if ($response->failed() && $response->status() !== 404) {
            $this->logFailure("failed to remove SKU {$listing->sku} from eBay", $response);
            throw new RuntimeException('Could not remove the listing from eBay: '.$this->errorMessage($response));
        }

        Log::info("eBay: SKU {$listing->sku} removed from \"{$account->store_name}\"".($listing->listing_id ? " (listing {$listing->listing_id} ended)" : ''));
    }

    /*
    |--------------------------------------------------------------------------
    | Orders (Fulfillment API)
    |--------------------------------------------------------------------------
    */

    /**
     * Orders created on eBay within the lookback window. Requires the
     * sell.fulfillment OAuth scope (re-connect stores authorized before it
     * was added to the scope list).
     */
    public function fetchOrders(EbayAccount $account, int $lookbackDays = 30): array
    {
        $since = now()->utc()->subDays($lookbackDays)->format('Y-m-d\TH:i:s.v\Z');
        $orders = [];
        $offset = 0;

        do {
            $response = $this->api($account)->get('/sell/fulfillment/v1/order', [
                'filter' => "creationdate:[{$since}..]",
                'limit' => 100,
                'offset' => $offset,
            ]);

            if ($response->failed()) {
                $this->logFailure("order fetch failed for store \"{$account->store_name}\"", $response);
                throw new RuntimeException('Could not fetch eBay orders: '.$this->errorMessage($response));
            }

            $orders = array_merge($orders, $response->json('orders', []));
            $offset += 100;
        } while ($response->json('next'));

        Log::info('eBay: '.count($orders)." orders fetched for store \"{$account->store_name}\" (last {$lookbackDays} days)");

        return $orders;
    }

    /*
    |--------------------------------------------------------------------------
    | Returns (Post-Order API)
    |--------------------------------------------------------------------------
    |
    | Buyer return requests are not part of the Fulfillment API: they live in
    | eBay's Post-Order API (https://developer.ebay.com/devzone/post-order/).
    | It accepts the same OAuth user token (sell.fulfillment scope) but with
    | the legacy "IAF" authorization scheme instead of "Bearer".
    */

    /**
     * Return requests opened against the seller within the lookback window.
     * Each member is a ReturnSummary: returnId, orderId, state, currentType,
     * creationInfo (item, reason, creationDate) and sellerTotalRefund.
     */
    public function fetchReturns(EbayAccount $account, int $lookbackDays = 30): array
    {
        // Both ends of the range are required when filtering by creation date.
        $window = [
            'creation_date_range_from' => now()->utc()->subDays($lookbackDays)->format('Y-m-d\TH:i:s.v\Z'),
            'creation_date_range_to' => now()->utc()->format('Y-m-d\TH:i:s.v\Z'),
        ];

        $returns = [];
        $offset = 0;

        do {
            $response = $this->postOrderApi($account)->get('/post-order/v2/return/search', $window + [
                'role' => 'SELLER',
                'limit' => 100,
                'offset' => $offset,
            ]);

            if ($response->failed()) {
                $this->logFailure("return search failed for store \"{$account->store_name}\"", $response);
                throw new RuntimeException('Could not fetch eBay returns: '.$this->errorMessage($response));
            }

            $members = $response->json('members', []);
            $returns = array_merge($returns, $members);
            $offset += count($members);
        } while ($members !== [] && $offset < (int) $response->json('total', 0));

        Log::info('eBay: '.count($returns)." returns fetched for store \"{$account->store_name}\" (last {$lookbackDays} days)");

        return $returns;
    }

    /**
     * Full detail of a single return: shipment tracking, refund breakdown,
     * response history. Used when the search summary is not enough.
     */
    public function fetchReturn(EbayAccount $account, string $returnId): array
    {
        $response = $this->postOrderApi($account)->get('/post-order/v2/return/'.rawurlencode($returnId));

        if ($response->failed()) {
            $this->logFailure("return {$returnId} fetch failed for store \"{$account->store_name}\"", $response);
            throw new RuntimeException('Could not fetch the eBay return: '.$this->errorMessage($response));
        }

        return $response->json();
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * HTTP client authorized as the given store.
     */
    private function api(EbayAccount $account): PendingRequest
    {
        return Http::withToken($this->ensureAccessToken($account))
            ->baseUrl($this->apiBase())
            ->acceptJson();
    }

    /**
     * HTTP client for the Post-Order API, which rejects "Bearer" tokens and
     * expects "IAF <token>" plus the marketplace header instead.
     */
    private function postOrderApi(EbayAccount $account): PendingRequest
    {
        return Http::withHeaders([
                'Authorization' => 'IAF '.$this->ensureAccessToken($account),
                'X-EBAY-C-MARKETPLACE-ID' => $account->marketplace_id,
            ])
            ->baseUrl($this->apiBase())
            ->acceptJson();
    }

    /**
     * eBay only accepts publicly reachable HTTPS image URLs.
     */
    private function imageUrls(?array $images): ?array
    {
        $urls = collect($images ?? [])
            ->map(fn (string $path) => Str::startsWith($path, ['http://', 'https://']) ? $path : asset($path))
            ->values()
            ->all();

        return $urls === [] ? null : $urls;
    }

    /**
     * Look up an existing offer id by SKU (used to recover from "offer exists").
     */
    private function findOfferId(EbayAccount $account, string $sku): ?string
    {
        $response = $this->api($account)->get('/sell/inventory/v1/offer', [
            'sku' => $sku,
            'marketplace_id' => $account->marketplace_id,
        ]);

        return $response->successful() ? $response->json('offers.0.offerId') : null;
    }

    private function hasErrorId(Response $response, int $errorId): bool
    {
        return collect($response->json('errors', []))->contains(fn (array $error) => ($error['errorId'] ?? null) === $errorId);
    }

    /**
     * Log a failed eBay response with its body so problems are easy to trace.
     */
    private function logFailure(string $action, Response $response): void
    {
        Log::error("eBay: {$action}", [
            'status' => $response->status(),
            'response' => Str::limit($response->body(), 1500),
        ]);
    }

    /**
     * Flatten eBay's error payload into a readable message.
     */
    private function errorMessage(Response $response): string
    {
        $errors = collect($response->json('errors', []))
            ->map(fn (array $error) => $error['longMessage'] ?? $error['message'] ?? '')
            ->filter()
            ->implode(' | ');

        if ($errors !== '') {
            return $errors;
        }

        return $response->json('error_description')
            ?? $response->json('error')
            ?? ('HTTP '.$response->status());
    }
}
