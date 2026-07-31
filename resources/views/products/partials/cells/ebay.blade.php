{{-- eBay sync badge plus the per-store detail modal it opens. --}}
@if ($product->ebayListings->isEmpty())
    <span class="text-muted small">—</span>
@else
    @php
        $statuses = $product->ebayListings->pluck('sync_status');
        $overall = $statuses->contains('failed') ? 'failed'
            : ($statuses->contains('pending') || $statuses->contains('syncing') ? 'pending' : 'synced');
        $badge = match ($overall) {
            'failed' => 'danger',
            'pending' => 'secondary',
            default => 'success',
        };
        $label = match ($overall) {
            'failed' => __('Attention'),
            'pending' => __('Pending'),
            default => __('Synced'),
        };
    @endphp
    <button type="button" class="btn p-0 border-0" data-bs-toggle="modal"
        data-bs-target="#ebayDetails{{ $product->id }}" title="{{ __('View eBay details') }}">
        <span class="badge bg-{{ $badge }}-subtle text-{{ $badge }}-emphasis border border-{{ $badge }}-subtle">
            <i class="fa-brands fa-ebay me-1"></i>{{ $label }}
            @if ($product->ebayListings->count() > 1)
                ({{ $product->ebayListings->count() }})
            @endif
        </span>
    </button>

    <div class="modal fade" id="ebayDetails{{ $product->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        <i class="fa-brands fa-ebay me-2"></i>{{ $product->name }} — {{ __('eBay Status') }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">{{ __('Store') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Last Synced') }}</th>
                                <th class="text-end pe-3">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product->ebayListings as $listing)
                                @php
                                    $rowBadge = match ($listing->sync_status) {
                                        'synced' => 'success',
                                        'failed' => 'danger',
                                        'syncing' => 'info',
                                        default => 'secondary',
                                    };
                                @endphp
                                <tr>
                                    <td class="ps-3 fw-medium">{{ $listing->ebayAccount->store_name ?? 'eBay' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $rowBadge }}-subtle text-{{ $rowBadge }}-emphasis border border-{{ $rowBadge }}-subtle">
                                            {{ ucfirst($listing->sync_status) }}
                                        </span>
                                        @if ($listing->sync_status === 'failed' && $listing->last_error)
                                            <div class="text-danger mt-1" style="font-size:11px; max-width:320px;">
                                                {{ Str::limit($listing->last_error, 180) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-secondary small">{{ $listing->last_synced_at?->format('M d, H:i') ?? '—' }}</td>
                                    <td class="text-end pe-3">
                                        <div class="d-flex justify-content-end gap-2">
                                            @if ($listing->listing_id)
                                                <a href="{{ (config('ebay.sandbox') ? 'https://sandbox.ebay.com/itm/' : 'https://www.ebay.com/itm/').$listing->listing_id }}"
                                                    target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary"
                                                    title="{{ __('View listing on eBay') }}">
                                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                </a>
                                            @endif
                                            @can('sync ebay products')
                                            <form method="POST" action="{{ route('ebay.listings.destroy', $listing) }}"
                                                onsubmit="return confirmDelete(event, '{{ __('Remove this product from eBay? The live listing will be ended.') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Remove from eBay') }}">
                                                    <i class="fa-solid fa-link-slash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
