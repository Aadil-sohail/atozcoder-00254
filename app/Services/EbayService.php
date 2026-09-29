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
    
    public static function mask(?string $secret): string
    {
        if (! $secret) {
            return '(none)';
        }

        return substr($secret, 0, 6).'…'.substr($secret, -4).' ('.strlen($secret).' chars)';
    }

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

    
    public function identityBase(): string
    {
        return config('ebay.sandbox')
            ? 'https://apiz.sandbox.ebay.com'
            : 'https://apiz.ebay.com';
    }

    /*
    |--------------------------------------------------------------------------
    | OAuth (connecting a store)
    |--------------------------------------------------------------------------
    */

    
    public function authorizationUrl(string $state): string
    {
        $query = [
            'client_id' => config('ebay.client_id'),
            'redirect_uri' => config('ebay.ru_name'),
            'response_type' => 'code',
            'scope' => implode(' ', config('ebay.scopes')),
            'state' => $state,
        ];

        
        if (config('ebay.force_login')) {
            $query['prompt'] = 'login';
        }

        $url = $this->authBase().'/oauth2/authorize?'.http_build_query($query);

        Log::info('eBay connect: consent URL built', [
            'environment' => config('ebay.sandbox') ? 'sandbox' : 'production',
            'auth_base' => $this->authBase(),
            'client_id' => config('ebay.client_id'),
            'ru_name' => config('ebay.ru_name'),
            'scopes' => config('ebay.scopes'),
            'force_login' => (bool) config('ebay.force_login'),
            'state' => $state,
            'url' => $url,
        ]);

        return $url;
    }

    
    public function exchangeCode(string $code): array
    {
        Log::info('eBay connect: exchanging authorization code for tokens', [
            'code' => self::mask($code),
            'token_url' => $this->apiBase().'/identity/v1/oauth2/token',
            'redirect_uri' => config('ebay.ru_name'),
        ]);

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

        $tokens = $response->json();

        Log::info('eBay connect: authorization code exchanged for tokens successfully', [
            'access_token' => self::mask($tokens['access_token'] ?? null),
            'access_token_expires_in' => $tokens['expires_in'] ?? null,
            'refresh_token' => self::mask($tokens['refresh_token'] ?? null),
            'refresh_token_expires_in' => $tokens['refresh_token_expires_in'] ?? null,
        ]);

        return $tokens;
    }

    
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

    
    public function fetchUsername(EbayAccount $account): ?string
    {
        $response = $this->api($account)->baseUrl($this->identityBase())->get('/commerce/identity/v1/user/');

        if (! $response->successful()) {
            $this->logFailure('seller identity lookup failed (commerce.identity scope missing?)', $response);

            return null;
        }

        Log::info('eBay connect: seller identified as "'.$response->json('username').'"');

        return $response->json('username');
    }

   
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

    
    private const SITE_IDS = [
        'EBAY_US' => '0', 'EBAY_CA' => '2', 'EBAY_GB' => '3', 'EBAY_AU' => '15',
        'EBAY_FR' => '71', 'EBAY_DE' => '77', 'EBAY_IT' => '101', 'EBAY_ES' => '186',
    ];

    
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

        $source = $product->connectionMaster();
        $quantity = max(0, (int) round($source->total_qty - $source->sold_qty));

        if ($quantity < 1 && ! $listing->listing_id) {
            throw new RuntimeException(sprintf(
                'No available stock to list (%s in total, %s already sold). eBay needs at least 1 unit in stock to publish a new listing — add stock, then sync again.',
                (float) $source->total_qty,
                (float) $source->sold_qty,
            ));
        }

       
        if ((float) $source->selling_price <= 0) {
            throw new RuntimeException(sprintf(
                'No selling price set for "%s" (cost %s). eBay cannot publish a listing priced at 0 — set a selling price on the product, then sync again.',
                $source->name,
                number_format((float) $source->cost_price, 2),
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
                    'value' => number_format((float) $source->selling_price, 2, '.', ''),
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
            throw new RuntimeException('Publish failed: '.$this->publishFailureMessage($account, $response));
        }

        $listing->update([
            'listing_id' => $response->json('listingId'),
            'sync_status' => 'synced',
            'last_error' => null,
            'last_synced_at' => now(),
        ]);

        Log::info("eBay: product #{$product->id} published as eBay listing {$listing->listing_id} on \"{$account->store_name}\"");
    }

    
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

    public function updateListingQuantity(EbayListing $listing, int $quantity): void
    {
        if (! $listing->listing_id) {
            throw new RuntimeException('That listing has no eBay item id yet, so its quantity cannot be set.');
        }

        $quantity = max(0, $quantity);
        $failures = [];

        foreach ($listing->offer_id ? ['offer', 'legacy'] : ['legacy', 'offer'] as $route) {
            try {
                $route === 'offer'
                    ? $this->updateOfferQuantity($listing, $quantity)
                    : $this->reviseListingQuantity($listing, $quantity);

                return;
            } catch (Throwable $e) {
                $failures[] = $e->getMessage();
            }
        }

        throw new RuntimeException(implode(' | ', array_unique($failures)));
    }

    /**
     * The Inventory API route: set the offer's available quantity, and the
     * stock behind the SKU it draws on.
     */
    private function updateOfferQuantity(EbayListing $listing, int $quantity): void
    {
        $account = $listing->ebayAccount;
        $offerId = $listing->offer_id ?: $this->findOfferId($account, $listing->sku);

        if (! $offerId) {
            throw new RuntimeException("No eBay offer found for SKU {$listing->sku}.");
        }

        $response = $this->api($account)->post('/sell/inventory/v1/bulk_update_price_quantity', [
            'requests' => [[
                'sku' => $listing->sku,
                'shipToLocationAvailability' => ['quantity' => $quantity],
                'offers' => [['offerId' => $offerId, 'availableQuantity' => $quantity]],
            ]],
        ]);

        if ($response->failed()) {
            throw new RuntimeException($this->errorMessage($response));
        }

        // A bulk call answers 200 even when the one request inside it failed.
        if ((int) ($response->json('responses.0.statusCode') ?? 200) >= 400) {
            throw new RuntimeException(
                $response->json('responses.0.errors.0.message')
                    ?: "eBay refused the quantity change on offer {$offerId}."
            );
        }

        Log::info("eBay: listing {$listing->listing_id} on \"{$account->store_name}\" set to {$quantity} available (offer {$offerId})");
    }

    /**
     * The legacy route, for listings the Inventory API never knew about —
     * which is most of a shop listed through eBay's own tools.
     */
    private function reviseListingQuantity(EbayListing $listing, int $quantity): void
    {
        $account = $listing->ebayAccount;
        $itemId = $listing->listing_id;

        $body = '<?xml version="1.0" encoding="utf-8"?>'
            .'<ReviseInventoryStatusRequest xmlns="urn:ebay:apis:eBLBaseComponents">'
            ."<InventoryStatus><ItemID>{$itemId}</ItemID><Quantity>{$quantity}</Quantity></InventoryStatus>"
            .'</ReviseInventoryStatusRequest>';

        $response = Http::withHeaders([
            'X-EBAY-API-CALL-NAME' => 'ReviseInventoryStatus',
            'X-EBAY-API-SITEID' => (string) config("ebay.marketplaces.{$account->marketplace_id}.site", 0),
            'X-EBAY-API-COMPATIBILITY-LEVEL' => '1155',
            'X-EBAY-API-IAF-TOKEN' => $this->ensureAccessToken($account),
            'Content-Type' => 'text/xml',
        ])->timeout(30)->withBody($body, 'text/xml')->post($this->tradingApiUrl());

        if ($response->failed()) {
            throw new RuntimeException("eBay returned HTTP {$response->status()} setting the quantity of listing {$itemId}.");
        }

        $xml = @simplexml_load_string($response->body());

        if ($xml === false) {
            throw new RuntimeException("eBay sent back unreadable XML setting the quantity of listing {$itemId}.");
        }

        if ((string) $xml->Ack === 'Failure') {
            throw new RuntimeException(
                (string) ($xml->Errors->LongMessage ?: $xml->Errors->ShortMessage)
                    ?: "eBay refused the quantity change on listing {$itemId}."
            );
        }

        Log::info("eBay: listing {$itemId} on \"{$account->store_name}\" set to {$quantity} available");
    }

    /*
    |--------------------------------------------------------------------------
    | Listing import (eBay -> software)
    |--------------------------------------------------------------------------
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

   
    private function publishFailureMessage(EbayAccount $account, Response $response): string
    {
        if ($this->hasErrorId($response, 25002)) {
            $privilege = $this->api($account)->get('/sell/account/v1/privilege');

            if ($privilege->successful() && $privilege->json('sellerRegistrationCompleted') === false) {
                Log::warning('eBay: publish blocked — seller "'.$account->ebay_username.'" has not completed seller registration on eBay');

                return sprintf(
                    'the eBay account "%s" has not finished seller registration, so eBay refuses to publish any listing (it reports only a generic system error). Sign in to eBay as that seller, complete the seller registration steps, then sync again.',
                    $account->ebay_username ?: $account->store_name,
                );
            }
        }

        return $this->errorMessage($response);
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
