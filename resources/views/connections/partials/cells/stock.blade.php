{{-- One pile of stock, however many stores the part is listed on. --}}
<span class="badge {{ $connection->available_stock <= 0 ? 'bg-danger-subtle text-danger-emphasis border border-danger-subtle' : 'bg-success-subtle text-success-emphasis border border-success-subtle' }}">
    {{ number_format((float) $connection->available_stock, 2) }}
</span>
