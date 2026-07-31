{{ ($item->quantity - $item->returned_qty) + 0 }}
@if ($item->returned_qty > 0)
    <span class="text-danger small">({{ $item->returned_qty + 0 }} {{ __('returned') }})</span>
@endif
