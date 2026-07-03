<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'ebay_account_id', 'sku', 'offer_id', 'listing_id', 'ebay_category_id', 'condition', 'sync_status', 'last_error', 'last_synced_at', 'inserted_by'])]
class EbayListing extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_synced_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function ebayAccount(): BelongsTo
    {
        return $this->belongsTo(EbayAccount::class);
    }
}
