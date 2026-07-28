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
                <span class="text-muted" style="font-size:12px;">{{ $products->count() }} {{ Str::plural('product', $products->count()) }} total</span>
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
                <a href="{{ route('products.create') }}" class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    {{ __('Add Product') }}
                </a>
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
                        <th>SKU</th>
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
                <tbody>
                    @foreach ($products as $product)
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
                    @endforeach
                </tbody>
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

    @can('create products')
        <div class="modal fade" id="excelImportModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form id="excel-import-form" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa-solid fa-file-excel me-2"></i>{{ __('Import Products from Excel') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted mb-3">
                            {{ __('Upload a supplier spreadsheet (xlsx, xls or csv) .') }}
                        </p>
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">{{ __('Category') }}</label>
                            <select id="excel-import-category" class="form-select form-select-sm" required>
                                <option value="" disabled selected>{{ __('Select a category') }}</option>
                                @foreach ($categories as $categoryOption)
                                    <option value="{{ $categoryOption->id }}">{{ $categoryOption->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <label class="form-label small text-muted mb-1">{{ __('Spreadsheet file') }}</label>
                                <a href="{{ asset('samples/product-import-sample.csv') }}" download class="small text-decoration-none">
                                    <i class="fa-solid fa-download me-1"></i>{{ __('Sample file') }}
                                </a>
                            </div>
                            <input type="file" id="excel-import-file" class="form-control form-control-sm" accept=".xlsx,.xls,.csv" required>
                        </div>
                        <div id="excel-import-progress" class="mt-3 d-none">
                            <div class="progress" style="height: 6px;">
                                <div id="excel-import-progress-bar" class="progress-bar bg-dark" style="width: 0%;"></div>
                            </div>
                            <p id="excel-import-progress-label" class="small text-muted mb-0 mt-1">{{ __('Uploading...') }}</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" id="excel-import-submit" class="btn btn-dark btn-sm">
                            <i class="fa-solid fa-upload me-1"></i>{{ __('Import') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endcan

@endsection

@push('scripts')
<script>
    $(function () {
        $('#products-table').DataTable({
            columnDefs: [
                { orderable: false, targets: 'no-sort' },
            ],
            order: [],
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
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

    // ── Chunked Excel import ─────────────────────────────────────
    // Large supplier catalogs (embedded photos, hundreds of rows) can be
    // tens of MB. Uploading in small chunks means the import never depends
    // on the server's post_max_size/upload_max_filesize configuration.
    const EXCEL_IMPORT_CHUNK_SIZE = 1 * 1024 * 1024;

    function generateUploadId() {
        if (window.crypto && crypto.randomUUID) {
            return crypto.randomUUID();
        }
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
            const r = Math.random() * 16 | 0;
            return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
    }

    function uploadExcelChunk(uploadId, chunk) {
        const formData = new FormData();
        formData.append('_token', @json(csrf_token()));
        formData.append('upload_id', uploadId);
        formData.append('chunk', chunk);

        return $.ajax({
            url: @json(route('products.import.chunk')),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
        });
    }

    function finalizeExcelImport(uploadId, filename, categoryId) {
        return $.ajax({
            url: @json(route('products.import.finalize')),
            method: 'POST',
            data: {
                _token: @json(csrf_token()),
                upload_id: uploadId,
                filename: filename,
                category_id: categoryId,
            },
        });
    }

    document.getElementById('excel-import-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const fileInput = document.getElementById('excel-import-file');
        const categoryId = document.getElementById('excel-import-category').value;
        const file = fileInput.files[0];

        if (! file || ! categoryId) {
            showAlert('warning', @json(__('Select a category and a file first.')));
            return;
        }

        const submitBtn = document.getElementById('excel-import-submit');
        const progress = document.getElementById('excel-import-progress');
        const progressBar = document.getElementById('excel-import-progress-bar');
        const progressLabel = document.getElementById('excel-import-progress-label');

        submitBtn.disabled = true;
        progress.classList.remove('d-none');

        const uploadId = generateUploadId();
        const totalChunks = Math.max(1, Math.ceil(file.size / EXCEL_IMPORT_CHUNK_SIZE));

        const resetForm = function () {
            submitBtn.disabled = false;
            progress.classList.add('d-none');
            progressBar.style.width = '0%';
        };

        const uploadNextChunk = function (index) {
            if (index >= totalChunks) {
                progressLabel.textContent = @json(__('Processing...'));
                progressBar.style.width = '100%';

                finalizeExcelImport(uploadId, file.name, categoryId)
                    .done(function (response) {
                        resetForm();
                        bootstrap.Modal.getInstance(document.getElementById('excelImportModal'))?.hide();
                        showAlert('success', response.message);
                        setTimeout(() => window.location.reload(), 1500);
                    })
                    .fail(function (xhr) {
                        resetForm();
                        showAlert('error', xhr.responseJSON?.message || @json(__('Import failed.')));
                    });

                return;
            }

            const start = index * EXCEL_IMPORT_CHUNK_SIZE;
            const chunk = file.slice(start, start + EXCEL_IMPORT_CHUNK_SIZE);

            uploadExcelChunk(uploadId, chunk)
                .done(function () {
                    const percent = Math.round(((index + 1) / totalChunks) * 100);
                    progressBar.style.width = percent + '%';
                    progressLabel.textContent = @json(__('Uploading...')) + ' ' + (index + 1) + '/' + totalChunks;
                    uploadNextChunk(index + 1);
                })
                .fail(function (xhr) {
                    resetForm();
                    showAlert('error', xhr.responseJSON?.message || @json(__('Upload failed.')));
                });
        };

        uploadNextChunk(0);
    });
</script>
@endpush
