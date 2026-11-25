<a href="{{ route('sales.show', $sale) }}" class="fw-medium text-decoration-none">
    {{ $sale->invoice_no }}
</a>
@if ((float) $sale->returned_units > 0)
    @if ((float) $sale->returned_units >= (float) $sale->sold_units)
        <span class="badge bg-danger ms-1">{{ __('Returned') }}</span>
    @else
        <span class="badge bg-warning text-dark ms-1">{{ __('Partly returned') }}</span>
    @endif
@endif
