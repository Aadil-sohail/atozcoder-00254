{{-- Nothing left on the shelf is called out in red, as it was in the old table. --}}
<span class="{{ $available <= 0 ? 'text-danger fw-semibold' : '' }}">{{ number_format((float) $available, 2) }}</span>
