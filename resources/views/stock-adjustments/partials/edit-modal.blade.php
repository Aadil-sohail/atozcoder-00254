{{-- Edit an adjustment: type, quantity and reason. Store and product stay as
     they were. Only the difference reaches the stock, and saving asks for
     confirmation first since that goes out to eBay. --}}
<div class="modal fade" id="edit-adjustment-modal" tabindex="-1" aria-labelledby="editAdjustmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="edit-adjustment-form" method="POST" action="" novalidate>
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="editAdjustmentLabel">{{ __('Edit Adjustment') }} <span id="edit-adjustment-id"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Store') }}</label>
                            <input id="edit-adjustment-store" type="text" class="form-control" disabled>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('Product') }}</label>
                            <input id="edit-adjustment-product" type="text" class="form-control" disabled>
                        </div>

                        <div class="col-md-6">
                            <label for="edit-adjustment-type" class="form-label">{{ __('Adjustment') }} <span class="text-danger">*</span></label>
                            <select id="edit-adjustment-type" name="type" class="form-select" required>
                                @foreach (\App\Models\StockAdjustment::TYPES as $value => $label)
                                    <option value="{{ $value }}">{{ __($label) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="edit-adjustment-quantity" class="form-label">{{ __('Quantity') }} <span class="text-danger">*</span></label>
                            <input id="edit-adjustment-quantity" name="quantity" type="number" step="0.01" min="0" class="form-control" required>
                        </div>

                        <div class="col-12">
                            <label for="edit-adjustment-reason" class="form-label">{{ __('Reason') }}</label>
                            <input id="edit-adjustment-reason" name="reason" type="text" maxlength="255" class="form-control">
                        </div>

                        <div class="col-12">
                            <div id="edit-adjustment-preview" class="alert alert-light border mb-0"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-dark">
                        <i class="fa-solid fa-check me-1"></i>{{ __('Update Adjustment') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function () {
        const modal = document.getElementById('edit-adjustment-modal');
        const form = document.getElementById('edit-adjustment-form');
        const type = document.getElementById('edit-adjustment-type');
        const quantity = document.getElementById('edit-adjustment-quantity');
        const reason = document.getElementById('edit-adjustment-reason');
        const preview = document.getElementById('edit-adjustment-preview');

        // The adjustment being edited: what it applied and what it started from.
        let original = null;

        const fmt = (n) => Number(n).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        const signed = (n) => (n >= 0 ? '+' : '') + fmt(n);

        // Rows are drawn by DataTables, so the click is delegated.
        $(document).on('click', '.edit-adjustment', function () {
            const d = this.dataset;

            original = { change: Number(d.change), before: Number(d.before), product: d.product };

            form.action = d.url;
            document.getElementById('edit-adjustment-id').textContent = '#' + d.id;
            document.getElementById('edit-adjustment-store').value = d.store || '—';
            document.getElementById('edit-adjustment-product').value = d.product;
            type.value = d.type;
            quantity.value = d.quantity;
            reason.value = d.reason || '';
            updatePreview();

            bootstrap.Modal.getOrCreateInstance(modal).show();
        });

        type.addEventListener('change', updatePreview);
        quantity.addEventListener('input', updatePreview);

        // The new change, measured from the stock the adjustment started at.
        function newChange() {
            const qty = Number(quantity.value);
            if (quantity.value === '' || isNaN(qty)) return null;

            return { increase: qty, decrease: -qty, set: qty - original.before }[type.value];
        }

        function updatePreview() {
            const change = newChange();

            if (change === null) {
                preview.textContent = 'Enter a quantity.';
                return;
            }

            const difference = change - original.change;

            preview.textContent = 'Recorded as: ' + fmt(original.before) + ' → ' + fmt(original.before + change)
                + ' (' + signed(change) + '). '
                + (difference === 0
                    ? 'The current stock does not change.'
                    : 'Current stock changes by ' + signed(difference) + '.');
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const qty = Number(quantity.value);
            const change = newChange();

            if (quantity.value === '' || isNaN(qty) || qty < 0 || (type.value !== 'set' && qty === 0)) {
                Toast.fire({ icon: 'warning', title: @json(__('Enter a valid quantity.')) });
                return;
            }

            if (change === 0) {
                Toast.fire({ icon: 'warning', title: @json(__('That leaves the stock where it started.')) });
                return;
            }

            const difference = change - original.change;

            // Only the reason changed: nothing goes to eBay, so no need to ask.
            if (difference === 0) {
                form.submit();
                return;
            }

            Swal.fire({
                title: @json(__('Update adjustment?')),
                html: $('<div>').text(original.product).html()
                    + '<br>' + @json(__('Current stock changes by')) + ' <b>' + signed(difference) + '</b>.<br><br>'
                    + @json(__('This adjustment will be pushed to the eBay stores as well: every listing of this product will be set to the new stock.')),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#212529',
                cancelButtonColor: '#6c757d',
                confirmButtonText: @json(__('Yes, update and push')),
                cancelButtonText: @json(__('No, cancel')),
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.querySelector('button[type="submit"]').disabled = true;
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
