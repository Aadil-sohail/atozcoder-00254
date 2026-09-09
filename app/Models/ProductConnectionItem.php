<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * One product inside a connection, and the stores it is listed on.
 */
#[Fillable(['product_connection_id', 'product_id'])]
class ProductConnectionItem extends Model
{
    public function connection(): BelongsTo
    {
        return $this->belongsTo(ProductConnection::class, 'product_connection_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * The stores this product sells on. Several, because one product row is
     * often listed on more than one store at a time.
     */
    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(
            EbayAccount::class,
            'connection_item_stores',
            'product_connection_item_id',
            'ebay_account_id',
        )->withTimestamps();
    }
}
