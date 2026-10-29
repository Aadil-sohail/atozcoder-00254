@php
    $colour = ['increase' => 'success', 'decrease' => 'danger'][$adjustment->type] ?? 'secondary';
@endphp
<span class="badge bg-{{ $colour }}-subtle text-{{ $colour }}-emphasis border border-{{ $colour }}-subtle">
    {{ $adjustment->typeLabel() }}
    @if ($adjustment->type === 'set')
        {{ number_format((float) $adjustment->quantity, 2) }}
    @endif
</span>
