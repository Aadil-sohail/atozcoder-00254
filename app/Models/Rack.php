<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'status', 'close', 'inserted_by'])]
class Rack extends Model
{
    public function shelves(): HasMany
    {
        return $this->hasMany(Shelf::class);
    }

    /**
     * The shelves still in use: a disabled shelf keeps its row (and its name
     * reserved) but no longer counts as part of the rack.
     */
    public function activeShelves(): HasMany
    {
        return $this->shelves()->where(['status' => '1', 'close' => '1']);
    }
}
