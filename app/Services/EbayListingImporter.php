<?php

namespace App\Services;

use App\Models\Category;
use App\Models\EbayAccount;
use App\Models\EbayImportItem;
use App\Models\EbayListing;
use App\Models\Inventory;
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
        $this->removeOrphanedListings($account);

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

    /*
    |--------------------------------------------------------------------------
    | Staged import (fetch everything, let the user pick, then save)
    |--------------------------------------------------------------------------
    */

    /**
     * Park everything the store has listed in ebay_import_items so the user can
     * choose what to keep. Nothing touches the products table here.
     *
     * Images are deliberately NOT downloaded yet, only their eBay URLs stored.
     * A shop of several hundred listings would otherwise spend minutes fetching
     * pictures for products nobody asked for.
     *
     * @return int how many listings are waiting to be picked
     */
    public function stage(EbayAccount $account): int
    {
        // A previous, abandoned run must not bleed into this one.
        $this->clearStaging($account);

        $rows = [];

        foreach ($this->collectListings($account) as $listing) {
            $this->extendTimeLimit();

            $item = $listing['item'];
            $sku = $item['sku'];
            $offer = $listing['offer'] ?? $this->resolveOffer($account, $sku);

            // No published offer means it is not actually listed on this store.
            if (! $offer) {
                continue;
            }

            $rows[] = [
                'ebay_account_id' => $account->id,
                'sku' => $sku,
                'title' => Str::limit(data_get($item, 'product.title') ?: $sku, 250, ''),
                'description' => data_get($item, 'product.description'),
                'image_urls' => json_encode(data_get($item, 'product.imageUrls', []) ?: []),
                'price' => data_get($offer, 'pricingSummary.price.value'),
                'quantity' => (float) (data_get($offer, 'availableQuantity')
                    ?? data_get($item, 'availability.shipToLocationAvailability.quantity', 0)),
                'ebay_category_id' => $offer['categoryId'] ?? null,
                'listing_id' => $offer['listing']['listingId'] ?? ($offer['listingId'] ?? null),
                'offer_id' => $offer['offerId'] ?? null,
                'condition' => $item['condition'] ?? 'NEW',
                'already_in_software' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $rows = $this->flagAlreadyImported($rows);

        foreach (array_chunk($rows, 200) as $chunk) {
            EbayImportItem::insert($chunk);
        }

        // The thumbnail count is worth knowing: GetMyeBaySelling is thin and
        // does not always carry a gallery image. A zero here means the import
        // screen shows placeholders — the products themselves still get their
        // full picture set from GetItem when they are saved.
        Log::info('eBay: '.count($rows)." listing(s) staged for store \"{$account->store_name}\", waiting on a selection", [
            'with_a_thumbnail' => count(array_filter($rows, fn (array $row) => $row['image_urls'] !== '[]')),
        ]);

        return count($rows);
    }

    /**
     * Import the picked rows, then clear the staging area for the store —
     * whether every row was picked or none of them were.
     *
     * @param  list<int|string>  $ids  ebay_import_items ids the user ticked
     * @return array{created: int, linked: int, skipped: int}
     */
    public function commit(EbayAccount $account, array $ids): array
    {
        $this->removeOrphanedListings($account);

        $created = 0;
        $linked = 0;
        $skipped = 0;

        EbayImportItem::where('ebay_account_id', $account->id)
            ->whereIn('id', $ids)
            ->with('ebayAccount')
            ->chunkById(100, function ($staged) use ($account, &$created, &$linked, &$skipped) {
                foreach ($staged as $row) {
                    $this->extendTimeLimit();

                    match ($this->importItem($account, $this->withListingDetails($account, $row), $row->toOffer())) {
                        'created' => $created++,
                        'linked' => $linked++,
                        default => $skipped++,
                    };
                }
            });

        $this->clearStaging($account);

        Log::info("eBay: selected import for store \"{$account->store_name}\" — {$created} created, {$linked} linked, {$skipped} skipped");

        return ['created' => $created, 'linked' => $linked, 'skipped' => $skipped];
    }

    /**
     * Top a staged row up with everything GetMyeBaySelling does not carry:
     * the full picture set, the listing description, and the item specifics
     * the seller filled in — which is where the warranty lives.
     *
     * Done here rather than at staging time on purpose. It costs one API call
     * per listing, which is nothing for the handful the user ticked and would
     * be several minutes for a shop of several hundred.
     *
     * @return array<string, mixed> the inventory-item shape importItem() reads
     */
    private function withListingDetails(EbayAccount $account, EbayImportItem $row): array
    {
        $item = $row->toInventoryItem();

        if (! $row->listing_id) {
            return $item;
        }

        $details = $this->fetchListingDetails($account, $row->listing_id);

        // Staging only ever had the gallery thumbnail, if that.
        if ($details['imageUrls'] !== []) {
            $item['product']['imageUrls'] = $details['imageUrls'];
        }

        $item['product']['description'] ??= $details['description'];
        $item['product']['aspects'] = $details['aspects'];
        $item['warranty_months'] = $this->warrantyMonths($details['aspects']);

        return $item;
    }

    /**
     * One listing in full, via the Trading API's GetItem.
     *
     * @return array{description: string|null, imageUrls: list<string>, aspects: array<string, list<string>>}
     */
    private function fetchListingDetails(EbayAccount $account, string $itemId): array
    {
        $empty = ['description' => null, 'imageUrls' => [], 'aspects' => []];

        $body = '<?xml version="1.0" encoding="utf-8"?>'
            .'<GetItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">'
            ."<ItemID>{$itemId}</ItemID>"
            .'<DetailLevel>ReturnAll</DetailLevel>'
            .'<IncludeItemSpecifics>true</IncludeItemSpecifics>'
            .'</GetItemRequest>';

        try {
            $response = Http::withHeaders([
                'X-EBAY-API-CALL-NAME' => 'GetItem',
                'X-EBAY-API-SITEID' => (string) config("ebay.marketplaces.{$account->marketplace_id}.site", 0),
                'X-EBAY-API-COMPATIBILITY-LEVEL' => '1155',
                'X-EBAY-API-IAF-TOKEN' => $this->ebay->ensureAccessToken($account),
                'Content-Type' => 'text/xml',
            ])->timeout(30)->withBody($body, 'text/xml')->post($this->tradingApiUrl());
        } catch (Throwable $e) {
            Log::warning("eBay: detail lookup failed for listing {$itemId}: ".$e->getMessage());

            return $empty;
        }

        if ($response->failed()) {
            Log::warning("eBay: detail lookup for listing {$itemId} returned HTTP {$response->status()}");

            return $empty;
        }

        $xml = @simplexml_load_string($response->body());

        if ($xml === false) {
            Log::warning("eBay: detail lookup for listing {$itemId} returned unreadable XML");

            return $empty;
        }

        if ((string) $xml->Ack === 'Failure') {
            Log::warning("eBay: detail lookup for listing {$itemId} rejected", [
                'error_code' => (string) $xml->Errors->ErrorCode,
                'error' => (string) $xml->Errors->LongMessage ?: (string) $xml->Errors->ShortMessage,
            ]);

            return $empty;
        }

        $images = [];

        foreach ($xml->Item->PictureDetails->PictureURL ?? [] as $url) {
            $images[] = (string) $url;
        }

        $aspects = [];

        foreach ($xml->Item->ItemSpecifics->NameValueList ?? [] as $pair) {
            $name = trim((string) $pair->Name);

            if ($name === '') {
                continue;
            }

            foreach ($pair->Value ?? [] as $value) {
                $aspects[$name][] = (string) $value;
            }
        }

        // eBay descriptions are seller-authored HTML and can run to tens of
        // kilobytes of markup. The product field is a plain text box, so the
        // markup is stripped and the result kept to something a column and a
        // human can both hold.
        $description = trim(html_entity_decode(strip_tags((string) $xml->Item->Description)));

        Log::info("eBay: listing {$itemId} detail fetched", [
            'images' => count($images),
            'item_specifics' => count($aspects),
            'has_description' => $description !== '',
        ]);

        return [
            'description' => $description === '' ? null : Str::limit($description, 5000),
            'imageUrls' => $images,
            'aspects' => $aspects,
        ];
    }

    /**
     * The seller's warranty from the listing's item specifics, as a number of
     * months the product form would accept.
     *
     * @param  array<string, list<string>>  $aspects
     */
    private function warrantyMonths(array $aspects): ?int
    {
        foreach ($aspects as $name => $values) {
            // "Seller Warranty", "Manufacturer Warranty", plain "Warranty" —
            // sellers label it differently and eBay does not standardise it.
            if (! Str::contains(Str::lower($name), 'warranty')) {
                continue;
            }

            if ($months = $this->parseWarrantyMonths((string) ($values[0] ?? ''))) {
                return $months;
            }
        }

        return null;
    }

    /**
     * "1 Month" / "2 Years" / "90 Days" as a month count the product form
     * offers, or null for "No warranty", "Lifetime" and anything unreadable.
     */
    private function parseWarrantyMonths(string $value): ?int
    {
        if (! preg_match('/(\d+)\s*(day|week|month|year)/i', $value, $matches)) {
            return null;
        }

        $count = (int) $matches[1];

        $months = match (Str::lower($matches[2])) {
            'day' => (int) round($count / 30),
            'week' => (int) round($count / 4.345),
            'year' => $count * 12,
            default => $count,
        };

        // The product form only offers these, so a value outside the list would
        // not survive the next edit. Rounded down, never up: an imported
        // warranty must not promise more than the seller actually offered.
        $fitted = null;

        foreach ([1, 2, 3, 4, 5, 6, 12] as $option) {
            if ($option <= $months) {
                $fitted = $option;
            }
        }

        return $fitted;
    }

    /**
     * Throw the staged rows away without importing any of them.
     */
    public function clearStaging(EbayAccount $account): int
    {
        return EbayImportItem::where('ebay_account_id', $account->id)->delete();
    }

    /**
     * Both sources merged into one list of {item, offer} pairs, keyed by SKU so
     * a listing the Inventory API already reported is not counted twice.
     *
     * @return list<array{item: array<string, mixed>, offer: array<string, mixed>|null}>
     */
    private function collectListings(EbayAccount $account): array
    {
        $listings = [];

        foreach ($this->ebay->fetchInventoryItems($account) as $item) {
            if ($sku = $item['sku'] ?? null) {
                // Its offer lives behind a separate per-SKU call, made only if
                // this item survives to the staging loop.
                $listings[$sku] = ['item' => $item, 'offer' => null];
            }
        }

        foreach ($this->fetchActiveListings($account) as $listing) {
            $listings[$listing['item']['sku']] ??= $listing;
        }

        return array_values($listings);
    }

    /**
     * Mark the rows whose SKU is already a product here, so the screen can show
     * that up front instead of the user discovering it after saving.
     *
     * @param  list<array<string, mixed>>  $rows
     * @return list<array<string, mixed>>
     */
    private function flagAlreadyImported(array $rows): array
    {
        $known = [];

        foreach (array_chunk(array_column($rows, 'sku'), 500) as $chunk) {
            $known += array_flip(Product::whereIn('sku', $chunk)->pluck('sku')->all());
        }

        foreach ($rows as $index => $row) {
            $rows[$index]['already_in_software'] = isset($known[$row['sku']]);
        }

        return $rows;
    }

    /**
     * The live offer for a SKU on this store's marketplace, or null when the
     * item has none, only a draft, or offers only on other marketplaces.
     *
     * @return array<string, mixed>|null
     */
    private function resolveOffer(EbayAccount $account, string $sku): ?array
    {
        return collect($this->ebay->fetchOffers($account, $sku))
            ->first(fn (array $candidate) => ($candidate['marketplaceId'] ?? $account->marketplace_id) === $account->marketplace_id
                && (($candidate['status'] ?? null) === 'PUBLISHED' || ! empty($candidate['listing']['listingId'])));
    }

    /**
     * Drop links whose product was deleted outside the app (e.g. straight from
     * the database). These orphans would otherwise block re-import and inflate
     * the store's linked count.
     */
    private function removeOrphanedListings(EbayAccount $account): int
    {
        $orphans = EbayListing::where('ebay_account_id', $account->id)->whereDoesntHave('product')->delete();

        if ($orphans > 0) {
            Log::info("eBay: removed {$orphans} orphaned listing(s) for store \"{$account->store_name}\" before import");
        }

        return $orphans;
    }

    /*
    |--------------------------------------------------------------------------
    | Fetching from eBay
    |--------------------------------------------------------------------------
    */

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

        $offer ??= $this->resolveOffer($account, $sku);

        if (! $offer) {
            Log::info("eBay: SKU {$sku} skipped, no published offer");

            return 'skipped';
        }

        $existing = Product::where('sku', $sku)->first();

        DB::transaction(function () use ($account, $item, $sku, $offer, $existing) {
            $product = $existing ?: $this->createProduct($item, $offer, $sku);

            // Stock lives in two places: products.total_qty is the running
            // balance the Inventory screen reads, and inventories is the ledger
            // saying where each movement came from. Creating the product
            // already set the balance, so this row explains it rather than
            // adding to it — no increment here, or the stock would double.
            // An existing product keeps whatever stock it already had.
            if (! $existing && $product->total_qty > 0) {
                Inventory::create([
                    'product_id' => $product->id,
                    'quantity' => $product->total_qty,
                    'inserted_by' => 'eBay Import',
                ]);
            }

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
            // Read off the listing's "Seller Warranty" item specific. Expiry is
            // counted from today, the same way the product form does it.
            'warranty_months' => $item['warranty_months'] ?? null,
            'warranty_expiry_date' => isset($item['warranty_months'])
                ? now()->addMonths($item['warranty_months'])->toDateString()
                : null,
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
