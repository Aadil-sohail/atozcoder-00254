{{-- How many eBay listings took the new figure; null when pushing is off. --}}
@if ($adjustment->pushed_listings === null)
    <span class="text-muted small">{{ __('Not pushed') }}</span>
@elseif ($adjustment->pushed_listings > 0)
    <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">
        <i class="fa-brands fa-ebay me-1"></i>{{ $adjustment->pushed_listings }} {{ Str::plural('listing', $adjustment->pushed_listings) }}
    </span>
@else
    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" title="{{ __('No listing was updated — see the log') }}">
        {{ __('Failed') }}
    </span>
@endif
