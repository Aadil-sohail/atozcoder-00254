@can('edit stock adjustments')
    {{-- Opens the edit modal on the index page, filled from these attributes. --}}
    <button type="button" class="btn btn-sm btn-outline-primary edit-adjustment" title="{{ __('Edit') }}"
        data-url="{{ route('stock-adjustments.update', $adjustment) }}"
        data-id="{{ $adjustment->id }}"
        data-product="{{ $adjustment->product_name }}{{ $adjustment->product_sku ? ' (' . $adjustment->product_sku . ')' : '' }}"
        data-store="{{ $adjustment->store_name }}"
        data-type="{{ $adjustment->type }}"
        data-quantity="{{ (float) $adjustment->quantity }}"
        data-change="{{ (float) $adjustment->change }}"
        data-before="{{ (float) $adjustment->stock_before }}"
        data-reason="{{ $adjustment->reason }}">
        <i class="fa-solid fa-pen"></i>
    </button>
@else
    <span class="text-muted">—</span>
@endcan
