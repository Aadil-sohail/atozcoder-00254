{{-- Margin is profit as a share of revenue: the number that compares a cheap
     part against an expensive one on equal terms. --}}
@if ($product->margin === null)
    <span class="text-muted">—</span>
@else
    @php($margin = (float) $product->margin)
    @php($tone = $margin < 0 ? 'danger' : ($margin < 10 ? 'warning' : ($margin < 25 ? 'info' : 'success')))
    <span class="badge bg-{{ $tone }}-subtle text-{{ $tone }}-emphasis border border-{{ $tone }}-subtle">
        {{ number_format($margin, 1) }}%
    </span>
@endif
