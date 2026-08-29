<?php

namespace App\Services;

use App\Models\Category;
use App\Models\EbayAccount;
use App\Models\EbayListing;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimpleXMLElement;
use Throwable;

class EbayListingImporter
{
    public function __construct(private EbayService $ebay)
    {
    }

    /**
     * Pull the store's eBay listings and create a local product for each one
     * that does not exist here yet, linking it back to the store. Items whose
     * SKU already matches a local product are linked without duplicating it.
     *
     * @return array{created: int, linked: int, skipped: int}
     */
    public function import(EbayAccount $account): array
    {
        // Remove links whose product was deleted outside the app (e.g. straight
        // from the database). These orphans would otherwise block re-import and
        // inflate the store's linked count.
        $orphans = EbayListing::where('ebay_account_id', $account->id)->whereDoesntHave('product')->delete();

        if ($orphans > 0) {
            Log::info("eBay: removed {$orphans} orphaned listing(s) for store \"{$account->store_name}\" before import");
        }

        $items = $this->ebay->fetchInventoryItems($account);

        $created = 0;
        $linked = 0;
        $skipped = 0;

        foreach ($items as $item) {
            $this->extendTimeLimit();

            match ($this->importItem($account, $item)) {
                'created' => $created++,
                'linked' => $linked++,
                default => $skipped++,
            };
        }

        // fetchInventoryItems() only knows items the Inventory API itself
        // created, so a shop listed through eBay's own tools — Seller Hub, the
        // mobile app, File Exchange, the legacy Trading API — reports nothing
        // there while being full of live products. Everything actually listed
        // on the account comes from the Trading API instead. SKUs already
        // handled above are skipped, and each listing carries its own offer, so
        // this pass needs no extra call per item.
        $alreadyHandled = array_flip(array_filter(array_column($items, 'sku')));

        foreach ($this->fetchActiveListings($account) as $listing) {
            if (isset($alreadyHandled[$listing['item']['sku']])) {
                continue;
            }

            $this->extendTimeLimit();

            match ($this->importItem($account, $listing['item'], $listing['offer'])) {
                'created' => $created++,
                'linked' => $linked++,
                default => $skipped++,
            };
        }

        Log::info("eBay: import for store \"{$account->store_name}\" — {$created} created, {$linked} linked, {$skipped} skipped");

        return ['created' => $created, 'linked' => $linked, 'skipped' => $skipped];
    }

    private function fetchActiveListings(EbayAccount $account): array
    {
        $listings = [];
        $page = 1;
        $totalPages = 1;

        do {
            $body = '<?xml version="1.0" encoding="utf-8"?>'
                .'<GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents">'
                .'<ActiveList><Include>true</Include>'
                ."<Pagination><EntriesPerPage>200</EntriesPerPage><PageNumber>{$page}</PageNumber></Pagination>"
                .'</ActiveList></GetMyeBaySellingRequest>';

            try {
                $response = Http::withHeaders([
                    'X-EBAY-API-CALL-NAME' => 'GetMyeBaySelling',
                    'X-EBAY-API-SITEID' => (string) config("ebay.marketplaces.{$account->marketplace_id}.site", 0),
                    'X-EBAY-API-COMPATIBILITY-LEVEL' => '1155',
                    'X-EBAY-API-IAF-TOKEN' => $this->ebay->ensureAccessToken($account),
                    'Content-Type' => 'text/xml',
                ])->timeout(60)->withBody($body, 'text/xml')->post($this->tradingApiUrl());
            } catch (Throwable $e) {
                Log::warning("eBay: active listing lookup failed for store \"{$account->store_name}\": ".$e->getMessage());

                break;
            }

            if ($response->failed()) {
                Log::warning("eBay: active listing lookup returned HTTP {$response->status()} for store \"{$account->store_name}\"");

                break;
            }

            $xml = @simplexml_load_string($response->body());

            if ($xml === false) {
                Log::warning("eBay: active listing lookup returned unreadable XML for store \"{$account->store_name}\"");

                break;
            }

            $xml->registerXPathNamespace('e', 'urn:ebay:apis:eBLBaseComponents');

          
            if ((string) $xml->Ack === 'Failure') {
                Log::warning("eBay: active listing lookup rejected for store \"{$account->store_name}\"", [
                    'error_code' => (string) $xml->Errors->ErrorCode,
                    'error' => (string) $xml->Errors->LongMessage ?: (string) $xml->Errors->ShortMessage,
                ]);

                break;
            }

            foreach ($xml->xpath('//e:ActiveList/e:ItemArray/e:Item') ?: [] as $item) {
                $listings[] = $this->legacyListingToInventoryShape($item, $account);
            }

            $totalPages = (int) ($xml->xpath('//e:ActiveList/e:PaginationResult/e:TotalNumberOfPages')[0] ?? 1);
            $page++;
        } while ($page <= $totalPages);

        Log::info('eBay: '.count($listings)." active listing(s) fetched for store \"{$account->store_name}\" (Trading API)");

        return $listings;
    }

    private function legacyListingToInventoryShape(SimpleXMLElement $item, EbayAccount $account): array
    {
        $itemId = (string) $item->ItemID;
        $sku = trim((string) $item->SKU);

        
        $sku = $sku !== '' ? $sku : 'EBAY-'.$itemId;

        $quantity = (float) $item->QuantityAvailable;

        if ($quantity <= 0) {
            $quantity = max(0, (float) $item->Quantity - (float) $item->SellingStatus->QuantitySold);
        }

        $images = [];

        foreach ($item->PictureDetails->PictureURL ?? [] as $url) {
            $images[] = (string) $url;
        }

        if ($images === [] && (string) $item->PictureDetails->GalleryURL !== '') {
            $images[] = (string) $item->PictureDetails->GalleryURL;
        }

        // A missing SimpleXML node reads as an empty element, never null, so ??
        // would not catch it: an empty price has to be tested for by hand.
        $price = (string) $item->SellingStatus->CurrentPrice;

        if ($price === '') {
            $price = (string) $item->StartPrice;
        }

        return [
            'item' => [
                'sku' => $sku,
                'condition' => $this->conditionFromLegacyId((string) $item->ConditionID),
                'product' => [
                    'title' => (string) $item->Title,
                    // GetMyeBaySelling does not carry the listing description.
                    'description' => null,
                    'imageUrls' => $images,
                ],
                'availability' => ['shipToLocationAvailability' => ['quantity' => $quantity]],
            ],
            'offer' => [
                'offerId' => null,
                // Already live on eBay by definition: it came from ActiveList.
                'status' => 'PUBLISHED',
                'listing' => ['listingId' => $itemId],
                'marketplaceId' => $account->marketplace_id,
                'categoryId' => (string) $item->PrimaryCategory->CategoryID ?: null,
                'availableQuantity' => $quantity,
                'pricingSummary' => ['price' => ['value' => $price !== '' ? $price : '0']],
            ],
        ];
    }

    /**
     * eBay's numeric ConditionID mapped onto the condition codes this app uses.
     * Unknown or absent ids fall back to NEW, matching the listings table default.
     */
    private function conditionFromLegacyId(string $conditionId): string
    {
        return match ($conditionId) {
            '1500', '1750' => 'NEW_OTHER',
            '2000', '2010', '2020', '2030', '2500', '2750' => 'LIKE_NEW',
            '3000' => 'USED_EXCELLENT',
            '4000' => 'USED_VERY_GOOD',
            '5000' => 'USED_GOOD',
            '6000' => 'USED_ACCEPTABLE',
            '7000' => 'FOR_PARTS_OR_NOT_WORKING',
            default => 'NEW',
        };
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

    /**
     * Give each product its own budget instead of racing one deadline for the
     * whole run: a shop with hundreds of listings downloads an image set per
     * product and would otherwise die partway through.
     */
    private function extendTimeLimit(): void
    {
        if (! str_contains((string) ini_get('disable_functions'), 'set_time_limit')) {
            @set_time_limit(60);
        }
    }

    
    private function importItem(EbayAccount $account, array $item, ?array $offer = null): string
    {
        $sku = $item['sku'] ?? null;

        if (! $sku) {
            return 'skipped';
        }

        // Look at any existing link for this store + SKU.
        $existingListing = EbayListing::where('ebay_account_id', $account->id)->where('sku', $sku)->first();

        if ($existingListing) {
            $linkedProduct = $existingListing->product;

            // Already imported and still active: nothing to do.
            if ($linkedProduct && $linkedProduct->status === '1') {
                return 'skipped';
            }

           
            if ($linkedProduct) {
                $linkedProduct->update(['status' => '1', 'close' => '1']);

                return 'linked';
            }

            $existingListing->delete();
        }

        $offer ??= collect($this->ebay->fetchOffers($account, $sku))
            ->first(fn (array $candidate) => ($candidate['marketplaceId'] ?? $account->marketplace_id) === $account->marketplace_id
                && (($candidate['status'] ?? null) === 'PUBLISHED' || ! empty($candidate['listing']['listingId'])));

        if (! $offer) {
            Log::info("eBay: SKU {$sku} skipped, no published offer");

            return 'skipped';
        }

        $existing = Product::where('sku', $sku)->first();

        DB::transaction(function () use ($account, $item, $sku, $offer, $existing) {
            $product = $existing ?: $this->createProduct($item, $offer, $sku);

            EbayListing::create([
                'product_id' => $product->id,
                'ebay_account_id' => $account->id,
                'sku' => $sku,
                'offer_id' => $offer['offerId'] ?? null,
                'listing_id' => $offer['listing']['listingId'] ?? ($offer['listingId'] ?? null),
                'ebay_category_id' => $offer['categoryId'] ?? null,
                'condition' => $item['condition'] ?? 'NEW',
                'sync_status' => 'synced',
                'last_synced_at' => now(),
                'inserted_by' => 'eBay Import',
            ]);
        });

        Log::info("eBay: SKU {$sku} imported from store \"{$account->store_name}\"".($existing ? " (linked to existing product #{$existing->id})" : ''));

        return $existing ? 'linked' : 'created';
    }

    /**
     * Build a local product from an eBay inventory item and its published offer.
     */
    private function createProduct(array $item, array $offer, string $sku): Product
    {
        $images = $this->downloadImages(data_get($item, 'product.imageUrls', []) ?? []);

        return Product::create([
            'name' => Str::limit(data_get($item, 'product.title') ?: $sku, 250, ''),
            'sku' => $sku,
            'description' => data_get($item, 'product.description'),
            'image' => $images === [] ? null : $images,
            'cost_price' => null, // eBay does not expose the seller's cost.
            'selling_price' => data_get($offer, 'pricingSummary.price.value'),
            'size' => data_get($item, 'product.aspects.Size.0'),
            'total_qty' => (float) (data_get($offer, 'availableQuantity')
                ?? data_get($item, 'availability.shipToLocationAvailability.quantity', 0)),
            'sold_qty' => 0,
            'category_id' => $this->importCategory()->id,
            'inserted_by' => 'eBay Import',
        ]);
    }

    /**
     * Shared category that imported eBay products are filed under.
     */
    private function importCategory(): Category
    {
        return Category::firstOrCreate(
            ['name' => 'eBay Imports'],
            ['inserted_by' => 'eBay Import'],
        );
    }

    /**
     * Download eBay's image URLs into the local uploads folder so the product
     * thumbnails behave exactly like manually added products. Failures are
     * logged and skipped rather than aborting the whole import.
     */
    private function downloadImages(array $urls): array
    {
        $paths = [];
        $directory = public_path('uploads/products');

        foreach (array_slice($urls, 0, 6) as $url) {
            try {
                // Kept short on purpose: a store with hundreds of listings
                // multiplies this wait by every image of every product.
                $response = Http::connectTimeout(5)->timeout(10)->get($url);

                if ($response->failed()) {
                    continue;
                }

                if (! is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }

                $extension = pathinfo((string) parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                $filename = uniqid('product_').'.'.$extension;

                file_put_contents($directory.DIRECTORY_SEPARATOR.$filename, $response->body());
                $paths[] = 'uploads/products/'.$filename;
            } catch (Throwable $e) {
                Log::warning("eBay: failed to download image {$url}: {$e->getMessage()}");
            }
        }

        return $paths;
    }
}
