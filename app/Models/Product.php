<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Collection;

#[Fillable(['name', 'sku', 'variant', 'description', 'image', 'cost_price', 'selling_price', 'unit_price', 'size', 'warranty_months', 'warranty_expiry_date', 'total_qty', 'sold_qty', 'category_id', 'subcategory_id', 'status', 'close', 'inserted_by'])]
class Product extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'image' => 'array',
            'warranty_expiry_date' => 'date',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function ebayListings(): HasMany
    {
        return $this->hasMany(EbayListing::class);
    }

    /**
     * This product's place in a connection, if it is part of one.
     */
    public function connectionItem(): HasOne
    {
        return $this->hasOne(ProductConnectionItem::class);
    }

    /**
     * The connection this product belongs to, if any.
     *
     * Not named connection(): Eloquent keeps the database connection name in
     * $model->connection, and that property would win over the relation.
     */
    public function productConnection(): HasOneThrough
    {
        return $this->hasOneThrough(
            ProductConnection::class,
            ProductConnectionItem::class,
            'product_id',
            'id',
            'id',
            'product_connection_id',
        );
    }

    /**
     * The row a connection speaks with: its master where there is a
     * connection, otherwise the product itself.
     *
     * Its price and its stock are the connection's, so every store the part is
     * listed on quotes the same figures — which is the point of connecting
     * them in the first place.
     */
    public function connectionMaster(): self
    {
        $master = $this->productConnection?->masterProduct;

        return $master && $master->id !== $this->id ? $master : $this;
    }

    /**
     * Leave out the copies a connection has folded into another row.
     *
     * A connected part keeps one product row per store, but only the master
     * holds the stock. Counting the rest again would have the same part on the
     * shelf twice, with figures that never move.
     */
    public function scopeWithoutConnectionDuplicates(Builder $query): Builder
    {
        return $query->whereNotExists(fn ($duplicate) => $duplicate
            ->selectRaw('1')
            ->from('product_connection_items as duplicate_item')
            ->join('product_connections as duplicate_connection', 'duplicate_connection.id', '=', 'duplicate_item.product_connection_id')
            ->whereColumn('duplicate_item.product_id', 'products.id')
            ->whereColumn('duplicate_connection.master_product_id', '!=', 'products.id'));
    }

    /**
     * Every eBay listing this product answers for: its own, plus those of the
     * other members when it is the face of a connection.
     *
     * @return Collection<int, EbayListing>
     */
    public function groupListings(): Collection
    {
        $connection = $this->productConnection;

        if (! $connection) {
            return $this->ebayListings;
        }

        return $connection->items
            ->flatMap(fn (ProductConnectionItem $item) => $item->product?->ebayListings ?? collect())
            ->unique('id')
            ->values();
    }

    /**
     * The stores this product is listed on, each named once.
     *
     * @return Collection<int, EbayAccount>
     */
    public function groupStores(): Collection
    {
        return $this->productConnection?->stores()
            ?? $this->ebayListings->map->ebayAccount->filter()->unique('id')->sortBy('store_name')->values();
    }

    /**
     * Units on the shelf: what was taken in, less what has been sold.
     */
    public function availableStock(): float
    {
        $keeper = $this->connectionMaster();

        return (float) $keeper->total_qty - (float) $keeper->sold_qty;
    }
}
