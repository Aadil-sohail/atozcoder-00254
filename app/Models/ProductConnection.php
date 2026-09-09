<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * One physical product that is listed on several stores under a separate
 * product row each.
 *
 * The master member is the one the connection speaks with: its cost and
 * selling price are the connection's prices, and its stock counters are the
 * connection's stock, so a sale on any store moves the same figure.
 */
#[Fillable(['name', 'master_product_id', 'status', 'close', 'inserted_by'])]
class ProductConnection extends Model
{
    public function items(): HasMany
    {
        return $this->hasMany(ProductConnectionItem::class);
    }

    public function masterProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'master_product_id');
    }

    /**
     * Every store the connection is listed on, each named once.
     *
     * Taken from the stores ticked for each member, topped up with any store
     * the member's own eBay listings point at — a product imported from two
     * stores carries a listing for each.
     *
     * @return Collection<int, EbayAccount>
     */
    public function stores(): Collection
    {
        return $this->items
            ->flatMap(fn (ProductConnectionItem $item) => $item->stores
                ->merge($item->product?->ebayListings->map->ebayAccount ?? []))
            ->filter()
            ->unique('id')
            ->sortBy('store_name')
            ->values();
    }
}
