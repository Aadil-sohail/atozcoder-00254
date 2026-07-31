<?php

namespace App\Services;

use App\Models\EbayAccount;
use App\Models\EbayListing;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use SimpleXMLElement;
use Throwable;

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
        'EBAY_US' => ['carrier' => 'USPS', 'services' => ['USPSPriority', 'USPSGroundAdvantage', 'USPSFirstClass', 'USPSParcel', 'ShippingMethodStandard']],
        'EBAY_GB' => ['carrier' => 'RoyalMail', 'services' => ['UK_RoyalMailSecondClassStandard', 'UK_RoyalMailFirstClassStandard', 'UK_RoyalMail48', 'UK_RoyalMail24']],
        'EBAY_CA' => ['carrier' => 'CanadaPost', 'services' => ['CA_RegularParcel', 'CA_ExpeditedParcel', 'CA_XpressPost']],
        'EBAY_AU' => ['carrier' => 'AustraliaPost', 'services' => ['AU_RegularParcelWithTracking', 'AU_RegularParcel', 'AU_Express', 'AU_StandardDelivery']],
        'EBAY_DE' => ['carrier' => 'DHL', 'services' => ['DE_DHLPaket', 'DE_DeutschePostBrief', 'DE_HermesPaket']],
        'EBAY_IT' => ['carrier' => 'PosteItaliane', 'services' => ['IT_PostaRaccomandata', 'IT_PostaOrdinaria', 'IT_PaccoCelere3', 'IT_QuickMail']],
        'EBAY_FR' => ['carrier' => 'LaPoste', 'services' => ['FR_LaPosteColissimo', 'FR_ColissimoAccess', 'FR_ColissimoAccessDomicileSansSignature']],
        'EBAY_ES' => ['carrier' => 'Correos', 'services' => ['ES_Estandar', 'ES_CorreosPostalExpress', 'ES_CorreosPaqueteAzul']],
    ];

    /**
     * eBay site IDs, needed by the Trading API (GeteBayDetails) to look up the
     * shipping services that are actually valid for a marketplace.
     */
    private const SITE_IDS = [
        'EBAY_US' => '0', 'EBAY_CA' => '2', 'EBAY_GB' => '3', 'EBAY_AU' => '15',
        'EBAY_FR' => '71', 'EBAY_DE' => '77', 'EBAY_IT' => '101', 'EBAY_ES' => '186',
    ];

    /**
     * Create a basic set of business policies (free domestic shipping, managed
     * payments, 30-day returns) and store their ids on the account.
     */
    public function createDefaultPolicies(EbayAccount $account): void
    {
        $this->optInToBusinessPolicies($account);

        $categoryTypes = [['name' => 'ALL_EXCLUDING_MOTORS_VEHICLES']];

        if (! $account->fulfillment_policy_id) {
            $response = null;

            // Candidates come from eBay's own list of valid domestic services for
            // this marketplace (so any marketplace works), with the hardcoded list
            // as a backup if that lookup is unavailable.
            foreach ($this->shippingServiceCandidates($account) as $candidate) {
                $shippingService = array_filter([
                    'sortOrder' => 1,
                    'shippingCarrierCode' => $candidate['carrier'] ?: null,
                    'shippingServiceCode' => $candidate['code'],
                    'freeShipping' => true,
                ], fn ($value) => $value !== null);

                $response = $this->api($account)->post('/sell/account/v1/fulfillment_policy', [
                    'name' => 'Default Shipping',
                    'marketplaceId' => $account->marketplace_id,
                    'categoryTypes' => $categoryTypes,
                    'handlingTime' => ['value' => 1, 'unit' => 'DAY'],
                    'shippingOptions' => [[
                        'costType' => 'FLAT_RATE',
                        'optionType' => 'DOMESTIC',
                        'shippingServices' => [$shippingService],
                    ]],
                ]);

                if ($response->successful()) {
                    Log::info("eBay: fulfillment policy created with shipping service \"{$candidate['code']}\"", ['policy_id' => $response->json('fulfillmentPolicyId')]);
                    break;
                }

                Log::warning("eBay: shipping service \"{$candidate['code']}\" rejected, trying next candidate", ['status' => $response->status()]);
            }

            if (! $response || $response->failed()) {
                $this->logFailure("all shipping service candidates rejected for {$account->marketplace_id}, fulfillment policy not created", $response);
                throw new RuntimeException(
                    "Could not create a default shipping policy for {$account->marketplace_id}. "
                    .'This usually means the connected seller is not eligible to sell on that marketplace. '
                    .'Reconnect this store choosing the marketplace that matches the seller, or create one shipping policy '
                    .'in the eBay Seller Hub for this marketplace and reload this page — it will be selected automatically. '
                    .'(eBay said: '.$this->errorMessage($response).')'
                );
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
     * Ordered list of shipping services to try when auto-creating a fulfillment
     * policy: eBay's own valid domestic services for the marketplace first (so
     * any marketplace works without hardcoding), then the static list as backup.
     *
     * @return array<int, array{carrier: string, code: string}>
     */
    private function shippingServiceCandidates(EbayAccount $account): array
    {
        $dynamic = $this->fetchDomesticShippingServices($account);

        $fallbackSet = self::DEFAULT_SHIPPING_SERVICES[$account->marketplace_id] ?? self::DEFAULT_SHIPPING_SERVICES['EBAY_US'];
        $fallback = array_map(
            fn (string $code) => ['carrier' => $fallbackSet['carrier'], 'code' => $code],
            $fallbackSet['services'],
        );

        // De-duplicate by service code, keeping the dynamic (authoritative) ones.
        return collect($dynamic)->merge($fallback)->unique('code')->values()->all();
    }

    /**
     * Domestic shipping services eBay reports as valid for the account's
     * marketplace, via the Trading API GeteBayDetails call. Returns an empty
     * list (so the caller falls back to the static codes) if anything fails.
     *
     * @return array<int, array{carrier: string, code: string}>
     */
    private function fetchDomesticShippingServices(EbayAccount $account): array
    {
        $siteId = self::SITE_IDS[$account->marketplace_id] ?? null;

        if ($siteId === null) {
            return [];
        }

        try {
            $response = Http::withHeaders([
                'X-EBAY-API-IAF-TOKEN' => $this->ensureAccessToken($account),
                'X-EBAY-API-SITEID' => $siteId,
                'X-EBAY-API-CALL-NAME' => 'GeteBayDetails',
                'X-EBAY-API-COMPATIBILITY-LEVEL' => '1193',
                'X-EBAY-API-DEV-NAME' => (string) config('ebay.dev_id'),
                'X-EBAY-API-APP-NAME' => (string) config('ebay.client_id'),
                'X-EBAY-API-CERT-NAME' => (string) config('ebay.client_secret'),
                'Content-Type' => 'text/xml',
            ])->withBody(
                '<?xml version="1.0" encoding="utf-8"?>'
                .'<GeteBayDetailsRequest xmlns="urn:ebay:apis:eBLBaseComponents">'
                .'<DetailName>ShippingServiceDetails</DetailName>'
                .'</GeteBayDetailsRequest>',
                'text/xml'
            )->post($this->apiBase().'/ws/api.dll');

            if ($response->failed()) {
                return [];
            }

            $xml = @simplexml_load_string($response->body());

            if ($xml === false) {
                return [];
            }

            $xml->registerXPathNamespace('e', 'urn:ebay:apis:eBLBaseComponents');
            $services = [];

            foreach ($xml->xpath('//e:ShippingServiceDetails') ?: [] as $node) {
                $node->registerXPathNamespace('e', 'urn:ebay:apis:eBLBaseComponents');

                $validForSelling = (string) ($node->xpath('e:ValidForSellingFlow')[0] ?? '') === 'true';
                $isInternational = $node->xpath('e:InternationalService') !== [];
                $code = (string) ($node->xpath('e:ShippingService')[0] ?? '');

                // Domestic, currently sellable services only.
                if ($validForSelling && ! $isInternational && $code !== '') {
                    $services[] = [
                        'carrier' => (string) ($node->xpath('e:ShippingCarrier')[0] ?? ''),
                        'code' => $code,
                    ];
                }
            }

            // Try at most a handful so a bad run does not fire dozens of requests.
            return array_slice($services, 0, 8);
        } catch (Throwable $e) {
            Log::warning("eBay: could not fetch shipping services for {$account->marketplace_id}: {$e->getMessage()}");

            return [];
        }
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

        // eBay rejects publishing a brand-new listing with zero available stock.
        // Catch it here with a clear message instead of eBay's cryptic one. An
        // already-live listing (has a listing_id) is allowed to drop to 0 so it
        // can be marked out of stock.
        if ($quantity < 1 && ! $listing->listing_id) {
            throw new RuntimeException(sprintf(
                'No available stock to list (%s in total, %s already sold). eBay needs at least 1 unit in stock to publish a new listing — add stock, then sync again.',
                (float) $product->total_qty,
                (float) $product->sold_qty,
            ));
        }

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
    | Listing import (eBay -> software)
    |--------------------------------------------------------------------------
    */

    /**
     * Every inventory item on the seller account (paginated). Each item holds
     * the SKU, product details (title, description, images, aspects) and the
     * available quantity. Requires the sell.inventory scope.
     */
    public function fetchInventoryItems(EbayAccount $account): array
    {
        $items = [];
        $offset = 0;

        do {
            $response = $this->api($account)->get('/sell/inventory/v1/inventory_item', [
                'limit' => 100,
                'offset' => $offset,
            ]);

            if ($response->failed()) {
                $this->logFailure("inventory item fetch failed for store \"{$account->store_name}\"", $response);
                throw new RuntimeException('Could not fetch eBay inventory: '.$this->errorMessage($response));
            }

            $items = array_merge($items, $response->json('inventoryItems', []));
            $offset += 100;
        } while ($response->json('next'));

        Log::info('eBay: '.count($items)." inventory items fetched for store \"{$account->store_name}\"");

        return $items;
    }

    /**
     * Offers for a single SKU. A published offer carries the live listing id,
     * price and eBay category, which is what makes an inventory item a real
     * listing rather than an unlisted draft.
     */
    public function fetchOffers(EbayAccount $account, string $sku): array
    {
        $response = $this->api($account)->get('/sell/inventory/v1/offer', [
            'sku' => $sku,
            'marketplace_id' => $account->marketplace_id,
        ]);

        // 404 means the SKU simply has no offers yet: treat as empty, not fatal.
        if ($response->status() === 404) {
            return [];
        }

        if ($response->failed()) {
            $this->logFailure("offer fetch failed for SKU {$sku}", $response);
            throw new RuntimeException('Could not fetch eBay offers: '.$this->errorMessage($response));
        }

        return $response->json('offers', []);
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

        $missing = $this->fetchLegacyOrders($account, $lookbackDays, $orders);

        if ($missing !== []) {
            Log::info('eBay: '.count($missing)." further orders recovered from the Trading API for store \"{$account->store_name}\"");
        }

        return array_merge($orders, $missing);
    }

    /**
     * Orders the Fulfillment search above cannot see.
     *
     * eBay only indexes an order for /sell/fulfillment/v1/order once its
     * payment has settled, so an unpaid order is invisible to that search
     * while being perfectly readable elsewhere — routinely the case in the
     * sandbox, where checkouts can sit at orderPaymentStatus=PENDING
     * indefinitely. The legacy Trading API lists those orders, so it is used
     * to fill the gap and the results are reshaped to look like Fulfillment
     * API orders, leaving EbayOrderImporter none the wiser.
     *
     * Orders already present in $known are skipped: an order that appears in
     * both is the same order, identified there by its legacyOrderId.
     *
     * @param  list<array<string, mixed>>  $known  orders the Fulfillment search returned
     * @return list<array<string, mixed>>
     */
    private function fetchLegacyOrders(EbayAccount $account, int $lookbackDays, array $known = []): array
    {
        $from = now()->utc()->subDays($lookbackDays)->format('Y-m-d\TH:i:s.v\Z');
        $to = now()->utc()->format('Y-m-d\TH:i:s.v\Z');

        $body = '<?xml version="1.0" encoding="utf-8"?>'
            .'<GetOrdersRequest xmlns="urn:ebay:apis:eBLBaseComponents">'
            ."<CreateTimeFrom>{$from}</CreateTimeFrom><CreateTimeTo>{$to}</CreateTimeTo>"
            .'<OrderRole>Seller</OrderRole><OrderStatus>All</OrderStatus>'
            .'<DetailLevel>ReturnAll</DetailLevel></GetOrdersRequest>';

        try {
            $response = Http::withHeaders([
                'X-EBAY-API-CALL-NAME' => 'GetOrders',
                'X-EBAY-API-SITEID' => (string) config("ebay.marketplaces.{$account->marketplace_id}.site", 0),
                'X-EBAY-API-COMPATIBILITY-LEVEL' => '1155',
                'X-EBAY-API-IAF-TOKEN' => $this->ensureAccessToken($account),
                'Content-Type' => 'text/xml',
            ])->withBody($body, 'text/xml')->post($this->tradingApiUrl());
        } catch (Throwable $e) {
            // A best-effort top-up: never let it break a sync that already
            // fetched orders successfully.
            Log::warning('eBay: Trading API order lookup failed: '.$e->getMessage());

            return [];
        }

        if ($response->failed()) {
            Log::warning('eBay: Trading API order lookup returned HTTP '.$response->status());

            return [];
        }

        $xml = @simplexml_load_string($response->body());

        if ($xml === false) {
            Log::warning('eBay: Trading API order lookup returned unreadable XML');

            return [];
        }

        $xml->registerXPathNamespace('e', 'urn:ebay:apis:eBLBaseComponents');

        // Both ids are compared: an order imported from this fallback is keyed
        // by its legacy id, which is what a Fulfillment order reports as its
        // legacyOrderId once the payment settles and it becomes searchable.
        $seen = collect($known)
            ->flatMap(fn (array $order) => [$order['orderId'] ?? null, $order['legacyOrderId'] ?? null])
            ->filter()
            ->all();

        $orders = [];

        foreach ($xml->xpath('//e:OrderArray/e:Order') ?: [] as $order) {
            $legacyId = (string) $order->OrderID;

            if ($legacyId === '' || in_array($legacyId, $seen, true)) {
                continue;
            }

            $orders[] = $this->legacyOrderToFulfillmentShape($order, $legacyId);
        }

        return $orders;
    }

    /**
     * Reshape one Trading API <Order> into the subset of the Fulfillment API
     * order shape that EbayOrderImporter reads.
     *
     * @return array<string, mixed>
     */
    private function legacyOrderToFulfillmentShape(SimpleXMLElement $order, string $legacyId): array
    {
        $lineItems = [];

        foreach ($order->TransactionArray->Transaction ?? [] as $transaction) {
            $quantity = (float) $transaction->QuantityPurchased;
            $unitPrice = (float) $transaction->TransactionPrice;

            $lineItems[] = [
                // The variation SKU wins where present, matching how the
                // Fulfillment API reports a variation's own SKU.
                'sku' => (string) ($transaction->Variation->SKU ?? '') ?: (string) ($transaction->Item->SKU ?? ''),
                'legacyItemId' => (string) $transaction->Item->ItemID,
                'title' => (string) $transaction->Item->Title,
                'quantity' => $quantity,
                // Fulfillment reports the line total; Trading reports unit price.
                'lineItemCost' => ['value' => (string) round($unitPrice * $quantity, 2)],
            ];
        }

        $address = $order->ShippingAddress ?? null;

        return [
            'orderId' => $legacyId,
            'legacyOrderId' => $legacyId,
            'creationDate' => (string) $order->CreatedTime,
            'orderPaymentStatus' => (string) ($order->CheckoutStatus->Status ?? ''),
            'cancelStatus' => [
                'cancelState' => (string) $order->OrderStatus === 'Cancelled' ? 'CANCELED' : 'NONE_REQUESTED',
            ],
            'buyer' => ['username' => (string) $order->BuyerUserID],
            'fulfillmentStartInstructions' => [[
                'shippingStep' => [
                    'shipTo' => [
                        'fullName' => (string) ($address->Name ?? ''),
                        'email' => (string) ($order->TransactionArray->Transaction[0]->Buyer->Email ?? '') ?: null,
                        'primaryPhone' => ['phoneNumber' => (string) ($address->Phone ?? '')],
                        'contactAddress' => [
                            'addressLine1' => (string) ($address->Street1 ?? ''),
                            'addressLine2' => (string) ($address->Street2 ?? ''),
                            'city' => (string) ($address->CityName ?? ''),
                            'stateOrProvince' => (string) ($address->StateOrProvince ?? ''),
                            'postalCode' => (string) ($address->PostalCode ?? ''),
                            'countryCode' => (string) ($address->Country ?? ''),
                        ],
                    ],
                ],
            ]],
            'lineItems' => $lineItems,
        ];
    }

    /**
     * Legacy Trading API endpoint for the active environment.
     */
    private function tradingApiUrl(): string
    {
        return config('ebay.sandbox')
            ? 'https://api.sandbox.ebay.com/ws/api.dll'
            : 'https://api.ebay.com/ws/api.dll';
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
