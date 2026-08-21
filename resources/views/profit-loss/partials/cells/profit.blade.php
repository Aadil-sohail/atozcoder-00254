@php($profit = (float) $product->profit)

@if ($product->qty_net <= 0)
    <span class="text-muted">—</span>
@elseif ($profit > 0)
    <span class="fw-semibold" style="color:#006300;">
        <i class="fa-solid fa-arrow-trend-up me-1" style="font-size:11px;"></i>{{ number_format($profit, 2) }}
    </span>
@elseif ($profit < 0)
    <span class="fw-semibold" style="color:#d03b3b;">
        <i class="fa-solid fa-arrow-trend-down me-1" style="font-size:11px;"></i>{{ number_format($profit, 2) }}
    </span>
@else
    <span class="fw-semibold text-secondary">{{ number_format(0, 2) }}</span>
@endif
