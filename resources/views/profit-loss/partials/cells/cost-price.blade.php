{{-- Without a cost price the whole sale value reads as profit, which would be
     a lie by omission — the row says so rather than showing 0.00. --}}
@if ($product->cost_price === null)
    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle"
        title="{{ __('No cost price recorded, so the full sale value is counted as profit.') }}">
        {{ __('Not set') }}
    </span>
@else
    {{ number_format((float) $product->cost_price, 2) }}
@endif
