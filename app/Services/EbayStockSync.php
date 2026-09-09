<?php

namespace App\Services;

use App\Models\EbayListing;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Sends a product's stock back out to eBay.
 *
 * The store an order came from lowers its own listing by itself; every other
 * store selling the same part knows nothing about it and would keep offering
 * stock that has gone. This tells them: whenever the figure here moves, every
 * listing of that product — on every store, across a connection — is set to
 * the same number.
 */
class EbayStockSync
{
    public function __construct(private EbayService $ebay) {}

    /**
     * Push the current stock of one product to eBay.
     *
     * Failures are logged, never thrown: a store that will not take the change
     * must not bring down the sale that caused it.
     *
     * @return int how many listings were updated
     */
    public function push(int|string $productId): int
    {
        if (! config('ebay.push_stock', true)) {
            return 0;
        }

        $product = Product::with('productConnection.masterProduct')->find($productId);

        if (! $product) {
            return 0;
        }

        // Oversold stock is still nothing to sell, and a negative would only
        // make the log read strangely.
        $quantity = max(0, (int) round($product->availableStock()));
        $updated = 0;

        foreach ($this->listingsFor($product) as $listing) {
            try {
                $this->ebay->updateListingQuantity($listing, $quantity);
                $updated++;
            } catch (Throwable $e) {
                Log::warning(sprintf(
                    'eBay: could not set listing %s on "%s" to %d: %s',
                    $listing->listing_id ?? '(no id)',
                    $listing->ebayAccount?->store_name ?? 'unknown store',
                    $quantity,
                    $e->getMessage(),
                ));
            }
        }

        return $updated;
    }

    /**
     * @param  iterable<int|string>  $productIds
     */
    public function pushMany(iterable $productIds): int
    {
        $updated = 0;

        foreach ($productIds as $productId) {
            $updated += $this->push($productId);
        }

        return $updated;
    }

    /**
     * Every live listing that should quote this product's stock: its own, and
     * those of the other members when it belongs to a connection.
     *
     * @return \Illuminate\Support\Collection<int, EbayListing>
     */
    private function listingsFor(Product $product): \Illuminate\Support\Collection
    {
        $productIds = $product->productConnection
            ? $product->productConnection->items->pluck('product_id')
            : collect([$product->id]);

        return EbayListing::with('ebayAccount')
            ->whereIn('product_id', $productIds)
            ->whereNotNull('listing_id')
            ->get();
    }
}
