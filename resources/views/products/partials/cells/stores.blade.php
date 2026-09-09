{{-- Which stores this part is listed on. A connected product carries the
     badges of every member, since the row stands for all of them. --}}
@php($stores = $product->groupStores())

@forelse ($stores as $store)
    <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle mb-1">
        {{ $store->store_name }}
    </span>
@empty
    <span class="text-muted small">—</span>
@endforelse

@if ($product->productConnection)
    <div>
        <span class="badge bg-dark-subtle text-dark-emphasis border" title="{{ __('Connected product: one price, one stock, shared across these stores') }}">
            <i class="fa-solid fa-link me-1"></i>{{ __('Connected') }}
        </span>
    </div>
@endif
