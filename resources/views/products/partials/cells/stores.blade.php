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

@if ($connection = $product->productConnection)
    <div>
        {{-- Opens the connection's members in a modal; see layouts/footer. --}}
        <button type="button" class="badge bg-dark-subtle text-dark-emphasis border connection-badge"
            title="{{ __('Show the connected products') }}">
            <i class="fa-solid fa-link me-1"></i>{{ __('Connected') }}
        </button>
        <template class="connection-details">
            @include('products.partials.connection-details', compact('connection'))
        </template>
    </div>
@endif
