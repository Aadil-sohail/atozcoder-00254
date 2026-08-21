{{-- The same product can go out at several prices, so the figure that matters
     is revenue over units. The spread underneath says how far it varied. --}}
@if ($product->qty_net > 0)
    <span class="fw-medium">{{ number_format((float) $product->avg_selling_price, 2) }}</span>
    @if ($product->min_price !== null && (float) $product->min_price != (float) $product->max_price)
        <div class="text-muted" style="font-size:11px;">
            {{ number_format((float) $product->min_price, 2) }} – {{ number_format((float) $product->max_price, 2) }}
        </div>
    @endif
@else
    <span class="text-muted">—</span>
@endif
