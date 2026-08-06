{{--
    Excel/CSV product import.

    Two steps in one modal: the file is uploaded in chunks and imported, then
    any rows whose chassis number matched no sub category come back here to be
    assigned one (or skipped) — see ProductController@importFinalize and
    @importResolve. Expects $subcategories and $ebayAccounts from the parent
    view: rows are identified by their eBay listing id, so the import needs a
    store to record that id against.
--}}
@can('create products')
    <div class="modal fade" id="excelImportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="excel-import-form" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-file-excel me-2"></i>{{ __('Import Products from Excel') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="excel-import-step-upload">
                        <p class="small text-muted mb-2">
                            {{ __('Upload a supplier spreadsheet (xlsx, xls or csv) .') }}
                        </p>
                        <ul class="small text-muted ps-3 mb-3">
                            <li>{{ __('Rows are matched on eBay Listing ID — an existing product is restocked, a new one is created and linked to that listing.') }}</li>
                            <li>{{ __('Each row\'s chassis number is its sub category — the product is filed under it and its category automatically.') }}</li>
                            <li>{{ __('A chassis number that isn\'t in the system yet is not imported; you pick a sub category for it in the next step.') }}</li>
                        </ul>

                        {{-- The listing ids in the file are recorded against this store, the
                             same place the eBay sync keeps them. --}}
                        @if ($ebayAccounts->isEmpty())
                            <div class="alert alert-warning small py-2 px-3 mb-0">
                                {{ __('Connect an eBay store first — the file identifies products by their eBay listing ID, which is stored against a store.') }}
                            </div>
                        @else
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">{{ __('eBay store') }}</label>
                            <select name="ebay_account_id" id="excel-import-account" class="form-select form-select-sm" required>
                                @foreach ($ebayAccounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->store_name }}
                                        ({{ config("ebay.marketplaces.{$account->marketplace_id}.label", $account->marketplace_id) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <label class="form-label small text-muted mb-1">{{ __('Spreadsheet file') }}</label>
                                @php
                                    // Stamped with the file's mtime so editing the sample invalidates
                                    // whatever copy the browser cached from an earlier download.
                                    $samplePath = public_path('samples/product-import-sample.csv');
                                    $sampleUrl = asset('samples/product-import-sample.csv')
                                        .(is_file($samplePath) ? '?v='.filemtime($samplePath) : '');
                                @endphp
                                <a href="{{ $sampleUrl }}" download class="small text-decoration-none">
                                    <i class="fa-solid fa-download me-1"></i>{{ __('Sample file') }}
                                </a>
                            </div>
                            <input type="file" id="excel-import-file" class="form-control form-control-sm"
                                accept=".xlsx,.xls,.csv" required>
                        </div>
                        <div id="excel-import-progress" class="mt-3 d-none">
                            <div class="progress">
                                <div id="excel-import-progress-bar" class="progress-bar bg-dark"></div>
                            </div>
                            <p id="excel-import-progress-label" class="small text-muted mb-0 mt-1">{{ __('Uploading...') }}</p>
                        </div>
                        @endif
                    </div>

                    {{-- Shown after an upload when some rows carried a chassis number
                         that matches no sub category. Those products were not imported;
                         each one needs a sub category before it can be. --}}
                    <div id="excel-import-step-assign" class="d-none">
                        <div id="excel-import-imported-note" class="alert alert-success small py-2 px-3 mb-3"></div>
                        <p class="small text-muted mb-2">
                            {{ __('These products were not imported — their chassis number is not a sub category in the software yet. Choose one for each, then import.') }}
                        </p>
                        <div id="excel-import-pending-list" class="d-flex flex-column gap-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="excel-import-cancel" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    {{-- Replaces Cancel on the assign step: keeps what already
                         imported and drops the rest instead of assigning them. --}}
                    <button type="button" id="excel-import-skip" class="btn btn-outline-secondary btn-sm d-none">
                        <i class="fa-solid fa-forward me-1"></i>{{ __('Skip & Finish') }}
                    </button>
                    <button type="submit" id="excel-import-submit" class="btn btn-dark btn-sm" @disabled($ebayAccounts->isEmpty())>
                        <i class="fa-solid fa-upload me-1"></i>{{ __('Import') }}
                    </button>
                    <button type="button" id="excel-import-assign-submit" class="btn btn-dark btn-sm d-none">
                        <i class="fa-solid fa-check me-1"></i>{{ __('Import Selected') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <style>
            #excel-import-progress .progress { height: 6px; }
            /* The pending list scrolls inside the modal so a long one never
               pushes the footer buttons off screen. */
            #excel-import-pending-list { max-height: 320px; overflow-y: auto; }
            #excel-import-pending-list .pending-subcategory { max-width: 65%; }
        </style>
    @endpush

    @push('scripts')
        <script>
            // ── Chunked Excel import ─────────────────────────────────────
            // Large supplier catalogs (embedded photos, hundreds of rows) can be
            // tens of MB. Uploading in small chunks means the import never depends
            // on the server's post_max_size/upload_max_filesize configuration.
            (function () {
                const CHUNK_SIZE = 1 * 1024 * 1024;

                // Offered for rows whose chassis number matched no sub category.
                const SUBCATEGORIES = @json($subcategories->map(fn ($s) => [
                    'id' => $s->id,
                    'label' => ($s->category->name ?? __('No category')).' › '.$s->name,
                ])->values());

                const modal = document.getElementById('excelImportModal');
                const form = document.getElementById('excel-import-form');
                const fileInput = document.getElementById('excel-import-file');
                const accountSelect = document.getElementById('excel-import-account');

                // Without a connected eBay store the upload step isn't rendered at
                // all — there is nowhere to record the listing ids from the file.
                if (! fileInput || ! accountSelect) {
                    return;
                }

                const stepUpload = document.getElementById('excel-import-step-upload');
                const stepAssign = document.getElementById('excel-import-step-assign');
                const pendingList = document.getElementById('excel-import-pending-list');
                const importedNote = document.getElementById('excel-import-imported-note');
                const progress = document.getElementById('excel-import-progress');
                const progressBar = document.getElementById('excel-import-progress-bar');
                const progressLabel = document.getElementById('excel-import-progress-label');
                const cancelBtn = document.getElementById('excel-import-cancel');
                const skipBtn = document.getElementById('excel-import-skip');
                const submitBtn = document.getElementById('excel-import-submit');
                const assignBtn = document.getElementById('excel-import-assign-submit');

                let uploadId = null;
                let summary = '';
                let pendingCount = 0;

                function generateUploadId() {
                    if (window.crypto && crypto.randomUUID) {
                        return crypto.randomUUID();
                    }
                    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
                        const r = Math.random() * 16 | 0;
                        return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
                    });
                }

                function uploadChunk(id, chunk) {
                    const formData = new FormData();
                    formData.append('_token', @json(csrf_token()));
                    formData.append('upload_id', id);
                    formData.append('chunk', chunk);

                    return $.ajax({
                        url: @json(route('products.import.chunk')),
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                    });
                }

                function finalize(id, filename) {
                    return $.ajax({
                        url: @json(route('products.import.finalize')),
                        method: 'POST',
                        data: {
                            _token: @json(csrf_token()),
                            upload_id: id,
                            filename: filename,
                            // The store the file's listing ids are recorded against.
                            ebay_account_id: accountSelect.value,
                        },
                    });
                }

                function closeAndReload(message) {
                    bootstrap.Modal.getInstance(modal)?.hide();
                    showAlert('success', message);
                    setTimeout(() => window.location.reload(), 1500);
                }

                // ── Step 1: upload and import what can be matched ────────
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    // On the assign step the file is already uploaded and the chosen file is
                    // still in the input, so a stray Enter must not start the upload again.
                    if (! stepAssign.classList.contains('d-none')) {
                        return;
                    }

                    const file = fileInput.files[0];

                    if (! file) {
                        showAlert('warning', @json(__('Select a file first.')));
                        return;
                    }

                    if (! accountSelect.value) {
                        showAlert('warning', @json(__('Choose the eBay store the listing IDs belong to first.')));
                        return;
                    }

                    submitBtn.disabled = true;
                    progress.classList.remove('d-none');

                    uploadId = generateUploadId();
                    const totalChunks = Math.max(1, Math.ceil(file.size / CHUNK_SIZE));

                    const reset = function () {
                        submitBtn.disabled = false;
                        progress.classList.add('d-none');
                        progressBar.style.width = '0%';
                    };

                    const uploadNextChunk = function (index) {
                        if (index >= totalChunks) {
                            progressLabel.textContent = @json(__('Processing...'));
                            progressBar.style.width = '100%';

                            finalize(uploadId, file.name)
                                .done(function (response) {
                                    reset();

                                    // Rows whose chassis number matched no sub category were not
                                    // imported — keep the modal open and ask for one per chassis.
                                    if (response.pending && response.pending.length) {
                                        showAssignStep(response.message, response.pending);
                                        return;
                                    }

                                    closeAndReload(response.message);
                                })
                                .fail(function (xhr) {
                                    reset();
                                    showAlert('error', xhr.responseJSON?.message || @json(__('Import failed.')));
                                });

                            return;
                        }

                        const start = index * CHUNK_SIZE;

                        uploadChunk(uploadId, file.slice(start, start + CHUNK_SIZE))
                            .done(function () {
                                progressBar.style.width = Math.round(((index + 1) / totalChunks) * 100) + '%';
                                progressLabel.textContent = @json(__('Uploading...')) + ' ' + (index + 1) + '/' + totalChunks;
                                uploadNextChunk(index + 1);
                            })
                            .fail(function (xhr) {
                                reset();
                                showAlert('error', xhr.responseJSON?.message || @json(__('Upload failed.')));
                            });
                    };

                    uploadNextChunk(0);
                });

                // ── Step 2: assign a sub category to what wasn't imported ────
                // The parsed rows stay on the server; the browser only sends back which
                // sub category each unmatched chassis number should be filed under.
                function showAssignStep(message, pending) {
                    summary = message;
                    pendingCount = pending.reduce((total, group) => total + group.products.length, 0);

                    importedNote.textContent = message;
                    // A required field inside a hidden step blocks the browser's own form
                    // validation, so it is only required while the upload step is showing.
                    fileInput.required = false;
                    stepUpload.classList.add('d-none');
                    stepAssign.classList.remove('d-none');
                    submitBtn.classList.add('d-none');
                    assignBtn.classList.remove('d-none');
                    cancelBtn.classList.add('d-none');
                    skipBtn.classList.remove('d-none');

                    pendingList.innerHTML = '';

                    pending.forEach(function (group) {
                        const wrap = document.createElement('div');
                        wrap.className = 'border rounded p-2';
                        wrap.dataset.chassis = group.chassis;

                        const head = document.createElement('div');
                        head.className = 'd-flex align-items-center justify-content-between gap-2 mb-2';

                        const label = document.createElement('span');
                        label.className = 'small text-nowrap';
                        label.innerHTML = @json(__('Chassis')) + ' <strong></strong>';
                        label.querySelector('strong').textContent = group.chassis;
                        head.appendChild(label);

                        const select = document.createElement('select');
                        select.className = 'form-select form-select-sm pending-subcategory';
                        const placeholder = document.createElement('option');
                        placeholder.value = '';
                        placeholder.textContent = @json(__('Select a sub category'));
                        select.appendChild(placeholder);
                        SUBCATEGORIES.forEach(function (s) {
                            const option = document.createElement('option');
                            option.value = s.id;
                            option.textContent = s.label;
                            select.appendChild(option);
                        });
                        head.appendChild(select);
                        wrap.appendChild(head);

                        const items = document.createElement('ul');
                        items.className = 'small text-muted mb-0 ps-3';
                        group.products.forEach(function (product) {
                            const item = document.createElement('li');
                            item.textContent = product.name
                                + (product.variant ? ' — ' + product.variant : '')
                                + ' (' + @json(__('Listing')) + ' ' + product.listing_id + ', ' + @json(__('qty')) + ' ' + product.quantity + ')';
                            items.appendChild(item);
                        });
                        wrap.appendChild(items);

                        pendingList.appendChild(wrap);
                    });
                }

                assignBtn.addEventListener('click', function () {
                    const mappings = [];

                    pendingList.querySelectorAll(':scope > div').forEach(function (wrap) {
                        const subcategoryId = wrap.querySelector('.pending-subcategory').value;

                        if (subcategoryId) {
                            mappings.push({ chassis: wrap.dataset.chassis, subcategory_id: subcategoryId });
                        }
                    });

                    if (mappings.length === 0) {
                        showAlert('warning', @json(__('Choose a sub category for at least one of them first.')));
                        return;
                    }

                    assignBtn.disabled = true;

                    $.ajax({
                        url: @json(route('products.import.resolve')),
                        method: 'POST',
                        data: {
                            _token: @json(csrf_token()),
                            upload_id: uploadId,
                            mappings: mappings,
                        },
                    })
                        .done(function (response) {
                            assignBtn.disabled = false;
                            closeAndReload(response.message);
                        })
                        .fail(function (xhr) {
                            assignBtn.disabled = false;
                            showAlert('error', xhr.responseJSON?.message || @json(__('Import failed.')));
                        });
                });

                // Leaves the unmatched products out of the software altogether. What the
                // upload already imported stays imported — only the rows still waiting for
                // a sub category are dropped, and re-uploading the file offers them again.
                skipBtn.addEventListener('click', function () {
                    closeAndReload(summary + ' ' + pendingCount + ' ' + @json(__('not imported (no sub category chosen).')));
                });

                // Closing the modal drops the half-finished import, so the next upload
                // starts from the file step rather than a stale list of pending rows.
                modal.addEventListener('hidden.bs.modal', function () {
                    uploadId = null;
                    form.reset();
                    fileInput.required = true;
                    pendingList.innerHTML = '';
                    stepUpload.classList.remove('d-none');
                    stepAssign.classList.add('d-none');
                    submitBtn.classList.remove('d-none');
                    assignBtn.classList.add('d-none');
                    cancelBtn.classList.remove('d-none');
                    skipBtn.classList.add('d-none');
                });
            })();
        </script>
    @endpush
@endcan
