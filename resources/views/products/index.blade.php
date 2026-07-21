@extends('layouts.header')

@section('title', 'Products')

@section('header')
    <h2 class="text-lg font-semibold leading-tight text-gray-800">
        {{ __('Products') }}
    </h2>
@endsection

@section('content')

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 p-2">
        <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
            <div>
                <p class="mb-0 text-muted small">{{ __('Manage the spare part products available across the system.') }}</p>
                <span class="text-muted" style="font-size:12px;">{{ $products->total() }} {{ Str::plural('product', $products->total()) }} total</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                @can('sync ebay products')
                    @if ($ebayAccounts->isNotEmpty())
                    <button type="button" class="btn btn-outline-dark btn-sm d-flex align-items-center gap-2"
                        data-bs-toggle="modal" data-bs-target="#ebayImportModal">
                        <i class="fa-brands fa-ebay"></i>
                        {{ __('Import from eBay') }}
                    </button>
                    <button type="button" class="btn btn-outline-dark btn-sm d-flex align-items-center gap-2" onclick="openEbaySyncModal()">
                        <i class="fa-brands fa-ebay"></i>
                        {{ __('Sync Selected to eBay') }}
                    </button>
                    @endif
                @endcan
                @can('create products')
                <a href="{{ route('products.create') }}" class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    {{ __('Add Product') }}
                </a>
                @endcan
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        @can('sync ebay products')
                            @if ($ebayAccounts->isNotEmpty())
                            <th style="width:32px;">
                                <input type="checkbox" class="form-check-input" onclick="toggleAllProducts(this)" title="{{ __('Select all') }}">
                            </th>
                            @endif
                        @endcan
                        <th style="width:60px;">{{ __('Image') }}</th>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Size</th>
                        <th>Cost</th>
                        <th>Selling</th>
                        @if ($ebayAccounts->isNotEmpty())
                        <th>eBay</th>
                        @endif
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            @can('sync ebay products')
                                @if ($ebayAccounts->isNotEmpty())
                                <td>
                                    <input type="checkbox" class="form-check-input product-select" value="{{ $product->id }}">
                                </td>
                                @endif
                            @endcan
                            <td>
                                @if (! empty($product->image))
                                    <img src="{{ asset($product->image[0]) }}" alt="{{ $product->name }}"
                                        class="rounded" style="width:40px; height:40px; object-fit:cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center rounded bg-light"
                                        style="width:40px; height:40px;">
                                        <i class="fa-solid fa-image text-secondary"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('products.show', $product) }}" class="fw-medium text-dark text-decoration-none">
                                    {{ $product->name }}
                                </a>
                            </td>
                            <td class="text-secondary">{{ $product->sku ?? '—' }}</td>
                            <td class="text-secondary">{{ $product->category->name ?? '—' }}</td>
                            <td class="text-secondary">{{ $product->size ?? '—' }}</td>
                            <td class="fw-medium">{{ number_format($product->cost_price, 2) }}</td>
                            <td class="fw-medium">{{ number_format($product->selling_price, 2) }}</td>
                            @if ($ebayAccounts->isNotEmpty())
                            <td>
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
                            </td>
                            @endif
                            <td class="text-left">
                                <div class="d-flex align-items-left justify-content-start gap-2">
                                    <a href="{{ route('products.show', $product) }}"
                                        class="btn btn-sm btn-outline-secondary" title="{{ __('View') }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @can('sync ebay products')
                                        @if ($ebayAccounts->isNotEmpty())
                                        <button type="button" class="btn btn-sm btn-outline-dark" title="{{ __('Sync to eBay') }}"
                                            onclick="openEbaySyncModal({{ $product->id }})">
                                            <i class="fa-brands fa-ebay"></i>
                                        </button>
                                        @endif
                                    @endcan
                                    @can('edit products')
                                    <a href="{{ route('products.edit', $product) }}"
                                        class="btn btn-sm btn-outline-primary" title="{{ __('Edit') }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    @endcan
                                    @can('delete products')
                                        @if ($product->status === '1')
                                            <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline"
                                                onsubmit="return confirm('{{ __('Disable this product?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Disable') }}">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="fa-solid fa-box fs-2 text-secondary opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-2">{{ __('No products found.') }}</p>
                                @can('create products')
                                    <a href="{{ route('products.create') }}" class="btn btn-sm btn-dark">
                                        {{ __('Add your first product') }}
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="card-footer bg-white">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    @can('sync ebay products')
        @if ($ebayAccounts->isNotEmpty())
        <div class="modal fade" id="ebayImportModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('ebay.sync-products') }}" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa-brands fa-ebay me-2"></i>{{ __('Import Products from eBay') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted mb-3">
                            {{ __('Products listed on the selected store but not yet in the software will be imported. Products already here are linked, not duplicated.') }}
                        </p>
                        <div class="mb-1">
                            <label class="form-label small text-muted mb-1">{{ __('eBay store') }}</label>
                            <select name="ebay_account_id" class="form-select form-select-sm" required>
                                @foreach ($ebayAccounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->store_name }}
                                        ({{ config("ebay.marketplaces.{$account->marketplace_id}.label", $account->marketplace_id) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-dark btn-sm">
                            <i class="fa-solid fa-download me-1"></i>{{ __('Import') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="ebaySyncModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('ebay.sync') }}" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa-brands fa-ebay me-2"></i>{{ __('Sync Products to eBay') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="ebay-sync-product-ids"></div>
                        <p class="small text-muted mb-3">
                            <span id="ebay-sync-count" class="fw-semibold">0</span> {{ __('product(s) will be synced.') }}
                        </p>
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">{{ __('eBay store') }}</label>
                            <select name="ebay_account_id" class="form-select form-select-sm" required>
                                @foreach ($ebayAccounts as $account)
                                    <option value="{{ $account->id }}" @disabled(! $account->isFullyConfigured())>
                                        {{ $account->store_name }}
                                        ({{ config("ebay.marketplaces.{$account->marketplace_id}.label", $account->marketplace_id) }})
                                        {{ $account->isFullyConfigured() ? '' : ' — '.__('setup needed') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">{{ __('Item condition') }}</label>
                            <select name="condition" class="form-select form-select-sm" required>
                                @foreach (config('ebay.conditions') as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1">
                            <label class="form-label small text-muted mb-1">{{ __('eBay category ID (optional)') }}</label>
                            <input type="text" name="ebay_category_id" class="form-control form-control-sm"
                                placeholder="{{ __('Leave empty to auto-suggest from the product title') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-dark btn-sm">
                            <i class="fa-solid fa-rotate me-1"></i>{{ __('Queue Sync') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    @endcan

@endsection

@push('scripts')
<script>
    function toggleAllProducts(source) {
        document.querySelectorAll('.product-select').forEach(cb => cb.checked = source.checked);
    }

    function openEbaySyncModal(productId = null) {
        const ids = productId !== null
            ? [productId]
            : Array.from(document.querySelectorAll('.product-select:checked')).map(cb => cb.value);

        if (ids.length === 0) {
            showAlert('warning', @json(__('Select at least one product first.')));
            return;
        }

        const container = document.getElementById('ebay-sync-product-ids');
        container.innerHTML = '';
        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'product_ids[]';
            input.value = id;
            container.appendChild(input);
        });
        document.getElementById('ebay-sync-count').textContent = ids.length;

        new bootstrap.Modal(document.getElementById('ebaySyncModal')).show();
    }
</script>
@endpush
