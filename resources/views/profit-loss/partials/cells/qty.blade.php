{{-- Units that stayed sold: returns are already netted out of qty_net, and a
     line returned in full never reaches this report at all. --}}
@php($trim = fn ($n) => rtrim(rtrim(number_format((float) $n, 2), '0'), '.'))

<span class="fw-medium">{{ $trim($product->qty_net) }}</span>
<div class="text-muted" style="font-size:11px;">
    {{ $product->sale_count }} {{ Str::plural('sale', (int) $product->sale_count) }}
</div>
