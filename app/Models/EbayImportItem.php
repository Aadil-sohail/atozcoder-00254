<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One eBay listing waiting on the import screen to be picked or dropped.
 *
 * Deliberately short-lived: EbayListingImporter clears every row for a store
 * as soon as the selection is saved or discarded.
 */
#[Fillable(['ebay_account_id', 'sku', 'title', 'description', 'image_urls', 'price', 'quantity', 'ebay_category_id', 'listing_id', 'offer_id', 'condition', 'already_in_software'])]
class EbayImportItem extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'image_urls' => 'array',
            'already_in_software' => 'boolean',
        ];
    }

    public function ebayAccount(): BelongsTo
    {
        return $this->belongsTo(EbayAccount::class);
    }

    /**
     * Rebuild the inventory-item shape the importer reads, so a staged row
     * imports through exactly the same path as a freshly fetched listing.
     *
     * @return array<string, mixed>
     */
    public function toInventoryItem(): array
    {
        return [
            'sku' => $this->sku,
            'condition' => $this->condition,
            'product' => [
                'title' => $this->title,
                'description' => $this->description,
                'imageUrls' => $this->image_urls ?? [],
            ],
            'availability' => ['shipToLocationAvailability' => ['quantity' => (float) $this->quantity]],
        ];
    }

    /**
     * The matching offer shape.
     *
     * @return array<string, mixed>
     */
    public function toOffer(): array
    {
        return [
            'offerId' => $this->offer_id,
            'status' => 'PUBLISHED',
            'listing' => ['listingId' => $this->listing_id],
            'marketplaceId' => $this->ebayAccount?->marketplace_id,
            'categoryId' => $this->ebay_category_id,
            'availableQuantity' => (float) $this->quantity,
            'pricingSummary' => ['price' => ['value' => (string) $this->price]],
        ];
    }
}
