<div class="fw-medium">{{ $connection->name }}</div>
<div class="text-muted" style="font-size:11px;">
    {{ __('Prices & stock from') }}: {{ $connection->masterProduct?->name ?? '—' }}
</div>
