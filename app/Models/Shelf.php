<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'rack_id', 'sort_order', 'status', 'close', 'inserted_by'])]
class Shelf extends Model
{
    public function rack(): BelongsTo
    {
        return $this->belongsTo(Rack::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Narrow a products query to the parts actually on a shelf: products in
     * use, each connected part once (as its master), the way the products
     * page lists them. A part listed on two stores is still one thing on the
     * shelf.
     */
    public static function stockedProducts(Builder $products): Builder
    {
        return $products
            ->where('products.status', '1')
            ->where('products.close', '1')
            ->withoutConnectionDuplicates();
    }

    /**
     * Shelves that can be picked for a product: in use, on a rack in use,
     * listed rack by rack in the order they were arranged.
     */
    public static function choices(): Collection
    {
        return static::with('rack')
            ->join('racks', 'racks.id', '=', 'shelves.rack_id')
            ->where(['shelves.status' => '1', 'shelves.close' => '1'])
            ->where(['racks.status' => '1', 'racks.close' => '1'])
            ->orderBy('racks.name')
            ->orderBy('shelves.sort_order')
            ->orderBy('shelves.id')
            ->select('shelves.*')
            ->get();
    }
}
