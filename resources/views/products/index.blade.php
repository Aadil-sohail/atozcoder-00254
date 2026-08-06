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
                <span class="text-muted" style="font-size:12px;">{{ $productCount }} {{ Str::plural('product', $productCount) }} total</span>
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
                <button type="button" class="btn btn-outline-dark btn-sm d-flex align-items-center gap-2"
                    data-bs-toggle="modal" data-bs-target="#excelImportModal">
                    <i class="fa-solid fa-file-excel"></i>
                    {{ __('Import from Excel') }}
                </button>
                {{-- <a href="{{ route('products.create') }}" class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    {{ __('Add Product') }}
                </a> --}}
                @endcan
            </div>
        </div>

        <div class="table-responsive">
            <table id="products-table" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        @can('sync ebay products')
                            @if ($ebayAccounts->isNotEmpty())
                            <th class="no-sort" style="width:32px;">
                                <input type="checkbox" class="form-check-input" onclick="toggleAllProducts(this)" title="{{ __('Select all') }}">
                            </th>
                            @endif
                        @endcan
                        <th class="no-sort" style="width:60px;">{{ __('Image') }}</th>
                        <th>Name</th>
                        <th>eBay Listing ID</th>
                        <th>Category</th>
                        <th>Size</th>
                        <th>Cost</th>
                        <th>Selling</th>
                        @if ($ebayAccounts->isNotEmpty())
                        <th>eBay</th>
                        @endif
                        <th class="no-sort">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
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

    @include('products.partials.excel-import-modal')

@endsection

@push('scripts')
<script>
    // Rows are fetched a page at a time from products.data — the catalog can
    // run to thousands of products, which is far too many to render up front.
    // The column list must line up with the <thead> above, including the
    // columns that only exist when an eBay store is connected.
    $(function () {
        serverTable('#products-table', {
            url: @json(route('products.data')),
            columns: [
                @can('sync ebay products')
                    @if ($ebayAccounts->isNotEmpty())
                    { data: 'select', orderable: false, searchable: false },
                    @endif
                @endcan
                { data: 'image', orderable: false, searchable: false },
                { data: 'name' },
                { data: 'listing_ids' },
                { data: 'category_name' },
                { data: 'size' },
                { data: 'cost_price' },
                { data: 'selling_price' },
                @if ($ebayAccounts->isNotEmpty())
                { data: 'ebay', orderable: false, searchable: false },
                @endif
                { data: 'actions', orderable: false, searchable: false },
            ],
            createdRow: function (row) {
                // Matches the cell classes the server-rendered table used.
                $('td', row).eq(-1).addClass('text-left');
            },
        });
    });

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
