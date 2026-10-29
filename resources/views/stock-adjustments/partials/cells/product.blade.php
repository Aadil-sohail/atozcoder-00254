<a href="{{ route('inventories.show', $adjustment->product_id) }}" class="fw-medium text-decoration-none">
    {{ $adjustment->product_name ?? '—' }}
</a>
@if ($adjustment->product_sku)
    <div class="text-muted small">{{ $adjustment->product_sku }}</div>
@endif
