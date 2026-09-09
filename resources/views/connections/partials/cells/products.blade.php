{{-- Every member, the master marked, so the row shows what was joined. --}}
@foreach ($connection->items as $item)
    <div class="small d-flex align-items-center gap-1">
        @if ($item->product_id === $connection->master_product_id)
            <i class="fa-solid fa-star text-warning" title="{{ __('Prices & stock come from this one') }}"
               style="font-size:10px;"></i>
        @endif
        <span>{{ $item->product?->name ?? __('(product removed)') }}</span>
    </div>
@endforeach
