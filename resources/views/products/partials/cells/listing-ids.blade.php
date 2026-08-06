{{-- The product's eBay listing id(s) — what the grid identifies a product by
     now that spreadsheet imports carry a listing id instead of a SKU. --}}
@php
    $listings = $product->ebayListings->whereNotNull('listing_id');
@endphp

@forelse ($listings as $listing)
    <div class="font-monospace small">{{ $listing->listing_id }}</div>
@empty
    <span class="text-muted small">—</span>
@endforelse
