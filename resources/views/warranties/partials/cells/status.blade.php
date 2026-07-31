@if ($fullyReturned)
    <span class="badge bg-danger">{{ __('Warranty Cancelled') }}</span>
@elseif ($daysLeft < 0)
    <span class="badge bg-secondary">{{ __('Warranty Ended') }}</span>
@else
    <span class="badge bg-success">{{ __('In Warranty') }}</span>
@endif
