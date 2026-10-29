{{-- New stock adjustment: store, then one of its products, then how to
     change the stock. Saving asks for confirmation first, since the new
     figure goes straight out to eBay. --}}
<div class="modal fade" id="adjustment-modal" tabindex="-1" aria-labelledby="adjustmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="adjustment-form" method="POST" action="{{ route('stock-adjustments.store') }}" novalidate>
                @csrf
                {{-- Lets the picked product be shown again after a failed save. --}}
                <input type="hidden" name="product_text" id="adjustment-product-text" value="{{ old('product_text') }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="adjustmentLabel">{{ __('New Stock Adjustment') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="adjustment-store" class="form-label">{{ __('Store') }} <span class="text-danger">*</span></label>
                            <select id="adjustment-store" name="ebay_account_id" class="form-select @error('ebay_account_id') is-invalid @enderror" required>
                                <option value="">{{ __('Select a store') }}</option>
                                @foreach ($ebayAccounts as $account)
                                    <option value="{{ $account->id }}" @selected(old('ebay_account_id') == $account->id)>{{ $account->store_name }}</option>
                                @endforeach
                            </select>
                            @error('ebay_account_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="adjustment-product" class="form-label">{{ __('Product') }} <span class="text-danger">*</span></label>
                            <select id="adjustment-product" name="product_id" class="form-select @error('product_id') is-invalid @enderror" required disabled>
                                @if (old('product_id'))
                                    <option value="{{ old('product_id') }}" selected>{{ old('product_text') }}</option>
                                @endif
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="adjustment-type" class="form-label">{{ __('Adjustment') }} <span class="text-danger">*</span></label>
                            <select id="adjustment-type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                                @foreach (\App\Models\StockAdjustment::TYPES as $value => $label)
                                    <option value="{{ $value }}" @selected(old('type', 'increase') === $value)>{{ __($label) }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="adjustment-quantity" class="form-label">{{ __('Quantity') }} <span class="text-danger">*</span></label>
                            <input id="adjustment-quantity" name="quantity" type="number" step="0.01" min="0" value="{{ old('quantity') }}"
                                class="form-control @error('quantity') is-invalid @enderror" required>
                            @error('quantity')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="adjustment-reason" class="form-label">{{ __('Reason') }}</label>
                            <input id="adjustment-reason" name="reason" type="text" maxlength="255" value="{{ old('reason') }}"
                                class="form-control @error('reason') is-invalid @enderror" placeholder="{{ __('e.g. damaged, stock count, found in warehouse') }}">
                            @error('reason')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Filled in as the form changes: current stock and what it will become. --}}
                        <div class="col-12">
                            <div id="adjustment-preview" class="alert alert-light border mb-0 d-none"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-dark">
                        <i class="fa-solid fa-check me-1"></i>{{ __('Save Adjustment') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function () {
        const modal = document.getElementById('adjustment-modal');
        const form = document.getElementById('adjustment-form');
        const $store = $('#adjustment-store');
        const $product = $('#adjustment-product');
        const type = document.getElementById('adjustment-type');
        const quantity = document.getElementById('adjustment-quantity');
        const productText = document.getElementById('adjustment-product-text');
        const preview = document.getElementById('adjustment-preview');

        // Stock of the picked product, as the picker reported it.
        let currentStock = null;

        const fmt = (n) => Number(n).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        // dropdownParent keeps the search box inside the modal, which would
        // otherwise refuse it focus.
        $store.select2({ theme: 'bootstrap-5', width: '100%', dropdownParent: $(modal) });

        // Products of the chosen store, searched on the server as the user types.
        $product.select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $(modal),
            placeholder: @json(__('Select a product')),
            ajax: {
                url: @json(route('stock-adjustments.products')),
                delay: 250,
                data: (params) => ({ store: $store.val(), q: params.term || '' }),
                processResults: (data) => data,
            },
            templateResult: (item) => {
                if (item.loading || item.stock === undefined) return item.text;

                const row = $('<div>');
                row.append($('<div>').text(item.text));
                row.append($('<div class="small text-muted">').text(
                    (item.listing_id ? 'Listing ' + item.listing_id + ' · ' : '') + 'Stock: ' + fmt(item.stock)
                ));
                return row;
            },
        });

        // A new store means a new list: drop the product picked from the old one.
        $store.on('change', function () {
            $product.val(null).trigger('change').prop('disabled', !this.value);
            currentStock = null;
            updatePreview();
        });

        $product.on('select2:select', function (e) {
            currentStock = Number(e.params.data.stock);
            productText.value = e.params.data.text;
            updatePreview();
        });

        type.addEventListener('change', updatePreview);
        quantity.addEventListener('input', updatePreview);

        function newStock() {
            const qty = Number(quantity.value);
            if (currentStock === null || quantity.value === '' || isNaN(qty)) return null;

            return { increase: currentStock + qty, decrease: currentStock - qty, set: qty }[type.value];
        }

        function updatePreview() {
            const after = newStock();

            if (currentStock === null) {
                preview.classList.add('d-none');
                return;
            }

            preview.classList.remove('d-none', 'alert-danger');
            preview.classList.add('alert-light');
            preview.textContent = after === null
                ? 'Current stock: ' + fmt(currentStock)
                : 'Stock: ' + fmt(currentStock) + ' → ' + fmt(after);

            if (after !== null && after < 0) {
                preview.classList.replace('alert-light', 'alert-danger');
                preview.textContent += ' — cannot go below zero.';
            }
        }

        // Checked here so the confirmation is only asked for a form that can
        // actually be saved; the server checks everything again.
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const qty = Number(quantity.value);
            let problem = null;

            if (!$store.val()) problem = @json(__('Select a store.'));
            else if (!$product.val()) problem = @json(__('Select a product.'));
            else if (quantity.value === '' || isNaN(qty) || qty < 0 || (type.value !== 'set' && qty === 0)) problem = @json(__('Enter a valid quantity.'));
            else if (currentStock !== null && newStock() < 0) problem = @json(__('The stock cannot go below zero.'));
            else if (currentStock !== null && newStock() === currentStock) problem = @json(__('That does not change the stock.'));

            if (problem) {
                Toast.fire({ icon: 'warning', title: problem });
                return;
            }

            const product = $product.select2('data')[0]?.text || productText.value;
            const change = currentStock === null
                ? ''
                : '<br>' + @json(__('Stock')) + ': <b>' + fmt(currentStock) + '</b> → <b>' + fmt(newStock()) + '</b>';

            Swal.fire({
                title: @json(__('Adjust stock?')),
                html: $('<div>').text(product).html() + change + '<br><br>'
                    + @json(__('This adjustment will be pushed to the eBay stores as well: every listing of this product will be set to the new stock.')),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#212529',
                cancelButtonColor: '#6c757d',
                confirmButtonText: @json(__('Yes, adjust and push')),
                cancelButtonText: @json(__('No, cancel')),
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.querySelector('button[type="submit"]').disabled = true;
                    form.submit();
                }
            });
        });

        // A failed save comes back with the form filled in; open it again.
        @if ($errors->any())
            if (@json(old('ebay_account_id'))) $product.prop('disabled', false);
            new bootstrap.Modal(modal).show();
        @endif
    });
</script>
@endpush
