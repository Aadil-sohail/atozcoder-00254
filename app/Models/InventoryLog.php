<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One stock movement, as recorded by App\Services\ProductStock.
 */
#[Fillable(['product_id', 'inventory_id', 'type', 'quantity', 'balance_after', 'sale_id', 'sale_return_id', 'ebay_account_id', 'note', 'inserted_by'])]
class InventoryLog extends Model
{
    public const STOCK_ADDED = 'stock_added';
    public const SALE = 'sale';
    public const EBAY_ORDER = 'ebay_order';
    public const RETURN = 'return';
    public const EBAY_RETURN = 'ebay_return';
    public const RETURN_DELETED = 'return_deleted';
    public const EXCEL_IMPORT = 'excel_import';
    public const EBAY_IMPORT = 'ebay_import';
    public const ADJUSTMENT = 'adjustment';

    /**
     * What each type is called on screen.
     */
    public const TYPES = [
        self::STOCK_ADDED => 'Stock added',
        self::SALE => 'Sale',
        self::EBAY_ORDER => 'eBay order',
        self::RETURN => 'Return',
        self::EBAY_RETURN => 'eBay return',
        self::RETURN_DELETED => 'Return deleted',
        self::EXCEL_IMPORT => 'Excel import',
        self::EBAY_IMPORT => 'eBay import',
        self::ADJUSTMENT => 'Adjustment',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function saleReturn(): BelongsTo
    {
        return $this->belongsTo(SaleReturn::class);
    }

    public function ebayAccount(): BelongsTo
    {
        return $this->belongsTo(EbayAccount::class);
    }

    /**
     * A stock entry that has no log row, shown in its place. Not saved: it
     * only lets the product's history list it alongside the real rows. The
     * stock left after it was never recorded, so that stays empty.
     */
    public static function fromInventory(Inventory $entry): self
    {
        $log = new self([
            'product_id' => $entry->product_id,
            'inventory_id' => $entry->id,
            'type' => $entry->inserted_by === 'eBay Import' ? self::EBAY_IMPORT : self::STOCK_ADDED,
            'quantity' => $entry->quantity,
            'inserted_by' => $entry->inserted_by,
        ]);

        $log->created_at = $entry->created_at;

        return $log;
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
