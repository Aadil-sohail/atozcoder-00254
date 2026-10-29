<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'ebay_account_id', 'type', 'quantity', 'change', 'stock_before', 'stock_after', 'pushed_listings', 'reason', 'status', 'close', 'inserted_by'])]
class StockAdjustment extends Model
{
    /**
     * What each adjustment type is called on screen.
     */
    public const TYPES = [
        'increase' => 'Increase',
        'decrease' => 'Decrease',
        'set' => 'Set stock to',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function ebayAccount(): BelongsTo
    {
        return $this->belongsTo(EbayAccount::class);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
