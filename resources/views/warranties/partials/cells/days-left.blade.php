@if ($fullyReturned)
    <span class="text-muted">—</span>
@elseif ($daysLeft < 0)
    <span class="badge bg-danger">{{ __('Expired') }} {{ abs($daysLeft) }} {{ Str::plural('day', abs($daysLeft)) }} {{ __('ago') }}</span>
@elseif ($daysLeft === 0)
    <span class="badge bg-danger">{{ __('Expires today') }}</span>
@elseif ($daysLeft <= 30)
    <span class="badge bg-warning text-dark">{{ $daysLeft }} {{ Str::plural('day', $daysLeft) }} {{ __('left') }}</span>
@else
    <span class="badge bg-success">{{ $daysLeft }} {{ Str::plural('day', $daysLeft) }} {{ __('left') }}</span>
@endif
