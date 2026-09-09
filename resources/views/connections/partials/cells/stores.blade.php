@php($stores = $connection->stores())

@forelse ($stores as $store)
    <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle mb-1">
        <i class="fa-brands fa-ebay me-1"></i>{{ $store->store_name }}
    </span>
@empty
    <span class="text-muted small">—</span>
@endforelse
