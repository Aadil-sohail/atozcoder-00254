@if ($adjustment->change >= 0)
    <span class="fw-semibold text-success">+{{ number_format((float) $adjustment->change, 2) }}</span>
@else
    <span class="fw-semibold text-danger">{{ number_format((float) $adjustment->change, 2) }}</span>
@endif
