{{-- The members of one connection, shown in the "Connected" modal. The
     preferred (master) product is the one whose price and stock count. --}}
@php
    $master = $connection->items->firstWhere('product_id', $connection->master_product_id)?->product;
@endphp

<div data-title="{{ $connection->name }}">
    <p class="text-muted small mb-3">
        {{ __('These products are one part listed on several stores. They share the price and stock of the preferred product.') }}
        @if ($master)
            <br>{{ __('Shared stock') }}:
            <span class="fw-semibold text-dark">{{ number_format((float) $master->total_qty - (float) $master->sold_qty, 2) }}</span>
        @endif
    </p>

    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>{{ __('Product') }}</th>
                    <th>{{ __('Store') }}</th>
                    <th>{{ __('eBay Listing ID') }}</th>
                    <th class="text-end">{{ __('Cost') }}</th>
                    <th class="text-end">{{ __('Selling') }}</th>
                </tr>
            </thead>
            <tbody>
                {{-- Preferred product first. --}}
                @foreach ($connection->items->sortByDesc(fn ($item) => $item->product_id == $connection->master_product_id) as $item)
                    @php
                        $member = $item->product;
                        $preferred = $item->product_id == $connection->master_product_id;
                        $memberStores = $item->stores->merge($member?->ebayListings->map->ebayAccount ?? [])->filter()->unique('id')->sortBy('store_name');
                    @endphp
                    <tr class="{{ $preferred ? 'table-success' : '' }}">
                        <td>
                            <span class="fw-medium">{{ $member->name ?? '—' }}</span>
                            @if ($preferred)
                                <span class="badge bg-success ms-1"><i class="fa-solid fa-star me-1"></i>{{ __('Preferred') }}</span>
                            @endif
                            @if ($member?->sku)
                                <div class="text-muted small">{{ $member->sku }}</div>
                            @endif
                        </td>
                        <td>
                            @forelse ($memberStores as $store)
                                <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">{{ $store->store_name }}</span>
                            @empty
                                <span class="text-muted">—</span>
                            @endforelse
                        </td>
                        <td class="font-monospace small">
                            {!! $member?->ebayListings->pluck('listing_id')->filter()->map(fn ($id) => e($id))->implode('<br>') ?: '—' !!}
                        </td>
                        <td class="text-end">{{ number_format((float) $member?->cost_price, 2) }}</td>
                        <td class="text-end">{{ number_format((float) $member?->selling_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
