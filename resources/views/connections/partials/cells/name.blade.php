<div class="fw-medium">{{ $connection->name }}</div>
<div class="text-muted" style="font-size:11px;">
    {{ __('Price & stock from') }}:
    {{ $connection->masterProduct?->name ?? '—' }}
    @if ($connection->masterStore)
        <span class="text-secondary">({{ $connection->masterStore->store_name }})</span>
    @endif
</div>
