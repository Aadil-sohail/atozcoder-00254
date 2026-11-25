<a href="{{ route('returns.show', $return) }}" class="fw-medium text-decoration-none">
    {{ $return->invoice_no }}
</a>
@if ($return->ebay_return_id)
    <span class="badge bg-primary ms-1" title="{{ __('eBay return') }} {{ $return->ebay_return_id }}">{{ __('eBay') }}</span>
@endif
