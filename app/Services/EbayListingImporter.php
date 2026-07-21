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
            match ($this->importItem($account, $item)) {
                'created' => $created++,
                'linked' => $linked++,
                default => $skipped++,
            };
        }

        Log::info("eBay: import for store \"{$account->store_name}\" — {$created} created, {$linked} linked, {$skipped} skipped");

        return ['created' => $created, 'linked' => $linked, 'skipped' => $skipped];
    }

    /**
     * Import one inventory item. Returns what happened so the caller can tally.
     *
     * @return 'created'|'linked'|'skipped'
     */
    private function importItem(EbayAccount $account, array $item): string
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

            // Product was disabled locally but is still listed on eBay: bring it
            // back rather than leaving it hidden and un-importable.
            if ($linkedProduct) {
                $linkedProduct->update(['status' => '1', 'close' => '1']);

                return 'linked';
            }

            // Orphaned link (product deleted outside the app): drop it so the
            // item can be re-imported as a fresh product below.
            $existingListing->delete();
        }

        // Only items with a published offer on THIS account's marketplace are
        // genuinely listed on this store. An item with no offer, only a draft
        // offer, or offers on a different marketplace is skipped. (Note: eBay
        // keeps one inventory per seller, so two stores that authorized the
        // same seller + marketplace are the same account and share listings.)
        $offer = collect($this->ebay->fetchOffers($account, $sku))
            ->first(fn (array $offer) => ($offer['marketplaceId'] ?? $account->marketplace_id) === $account->marketplace_id
                && (($offer['status'] ?? null) === 'PUBLISHED' || ! empty($offer['listing']['listingId'])));

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
                $response = Http::timeout(15)->get($url);

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
