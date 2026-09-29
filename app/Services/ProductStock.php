<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductConnectionItem;

/**
 * Every movement of stock, wherever it comes from — a sale typed in by hand,
 * an eBay order, a return, a delivery booked in — passes through here.
 *
 * A connected product has no stock of its own: the pile belongs to its
 * connection's master, so all the stores it is listed on are quoting the same
 * units. Sending the movements through one place is what keeps that true; a
 * bare increment on the row that happened to sell would leave every other
 * store's figure behind.
 */
class ProductStock
{
    /**
     * Masters already looked up in this request, keyed by product id. A sale
     * of several lines, or an order sync of several hundred, would otherwise
     * ask the same question over and over.
     *
     * @var array<int, int>
     */
    private static array $keepers = [];

    /**
     * The product whose counters hold this one's stock.
     */
    public static function keeperId(int|string $productId): int
    {
        $productId = (int) $productId;

        return self::$keepers[$productId] ??= (int) (ProductConnectionItem::query()
            ->join('product_connections', 'product_connections.id', '=', 'product_connection_items.product_connection_id')
            ->where('product_connection_items.product_id', $productId)
            ->where('product_connections.status', '1')
            ->value('product_connections.master_product_id') ?: $productId);
    }

    /**
     * Products whose stock moved in this request, waiting to be sent to eBay.
     *
     * @var array<int, int>
     */
    private static array $moved = [];

    private static bool $flushRegistered = false;

    /**
     * Units left the shelf.
     */
    public static function sold(int|string $productId, float|int|string $quantity): void
    {
        Product::where('id', self::keeperId($productId))->increment('sold_qty', $quantity);

        self::moved($productId);
    }

    /**
     * Units came back — a return, or a sale undone.
     */
    public static function returned(int|string $productId, float|int|string $quantity): void
    {
        Product::where('id', self::keeperId($productId))->decrement('sold_qty', $quantity);

        self::moved($productId);
    }

    /**
     * Units were taken into stock.
     */
    public static function received(int|string $productId, float|int|string $quantity): void
    {
        Product::where('id', self::keeperId($productId))->increment('total_qty', $quantity);

        // Stock booked in from the Add Stock modal is not pushed to the eBay
        // stores for now — they pick up the new figure on the next sale, return
        // or manual resync. Uncomment to send it straight away again.
        // self::moved($productId);
    }

    /**
     * Note that this product's stock changed, and arrange for eBay to be told
     * once the work is done.
     *
     * Held back rather than sent on the spot for two reasons: a sale of five
     * lines would otherwise call eBay five times with figures that are still
     * moving, and the call would land inside the database transaction that is
     * writing the sale.
     */
    private static function moved(int|string $productId): void
    {
        $keeper = self::keeperId($productId);

        self::$moved[$keeper] = $keeper;

        if (! self::$flushRegistered) {
            self::$flushRegistered = true;

            // Runs once the response has gone out, or once an artisan command
            // has finished, so nobody waits on eBay for it.
            app()->terminating(fn () => self::flush());
        }
    }

    /**
     * Send every stock figure that moved to the stores listing that product.
     */
    public static function flush(): int
    {
        $ids = self::$moved;

        self::$moved = [];

        // Cleared so the next movement books itself a fresh callback. Without
        // this, only the first request of a long-running process — a queue
        // worker, or the test suite — would ever reach eBay.
        self::$flushRegistered = false;

        return $ids === [] ? 0 : app(EbayStockSync::class)->pushMany($ids);
    }

    /**
     * Drop what has been remembered — for tests, and for any long-running
     * process that outlives a connection being changed underneath it.
     */
    public static function forget(): void
    {
        self::$keepers = [];
        self::$moved = [];
        self::$flushRegistered = false;
    }
}
