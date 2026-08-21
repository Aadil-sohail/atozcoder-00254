{{-- Opens the per-sale breakdown behind the row's averages. --}}
<button type="button" class="btn btn-sm btn-outline-secondary"
    title="{{ __('View every sale of this product') }}"
    onclick="openProfitBreakdown({{ $product->id }}, this)"
    @disabled($product->qty_net <= 0)>
    <i class="fa-solid fa-list-ul"></i>
</button>
