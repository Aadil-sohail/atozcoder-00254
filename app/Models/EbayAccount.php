<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['store_name', 'ebay_username', 'marketplace_id', 'access_token', 'access_token_expires_at', 'refresh_token', 'refresh_token_expires_at', 'fulfillment_policy_id', 'payment_policy_id', 'return_policy_id', 'merchant_location_key', 'status', 'inserted_by'])]
class EbayAccount extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'access_token' => 'encrypted',
            'refresh_token' => 'encrypted',
            'access_token_expires_at' => 'datetime',
            'refresh_token_expires_at' => 'datetime',
        ];
    }

    public function listings(): HasMany
    {
        return $this->hasMany(EbayListing::class);
    }

    /**
     * The cached access token is still usable (with a small safety margin).
     */
    public function hasValidAccessToken(): bool
    {
        return $this->access_token !== null
            && $this->access_token_expires_at !== null
            && $this->access_token_expires_at->subMinutes(5)->isFuture();
    }

    /**
     * The ~18 month refresh token has expired and the store must be re-connected.
     */
    public function needsReconnect(): bool
    {
        return $this->refresh_token_expires_at !== null
            && $this->refresh_token_expires_at->isPast();
    }

    /**
     * Business policies and inventory location are set, so offers can be published.
     */
    public function isFullyConfigured(): bool
    {
        return $this->fulfillment_policy_id !== null
            && $this->payment_policy_id !== null
            && $this->return_policy_id !== null
            && $this->merchant_location_key !== null;
    }
}
