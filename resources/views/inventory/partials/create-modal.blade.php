@php
    $oldItems = old('items', [['product_id' => '', 'quantity' => '']]);
@endphp

<div class="modal fade {{ $errors->any() ? 'show d-block' : '' }}"
    id="create-inventory-modal" tabindex="-1" aria-labelledby="createInventoryLabel" aria-modal="true"
    style="{{ $errors->any() ? 'background:rgba(0,0,0,0.5)' : '' }}">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="POST" action="{{ route('inventories.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="createInventoryLabel">{{ __('Add Stock') }}</h5>
                    <button type="button" class="btn-close" onclick="closeStockModal()"></button>
                </div>

                <div class="modal-body">
                    @error('items')
                        <div class="alert alert-warning py-2">{{ $message }}</div>
                    @enderror

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="fw-medium">{{ __('Products') }}</span>
                        <button type="button" onclick="addStockRow()" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-plus me-1"></i>{{ __('Add Product') }}
                        </button>
                    </div>

                    <div id="stock-rows"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeStockModal()" class="btn btn-outline-secondary">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-dark">{{ __('Save Stock') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Hidden template row (cloned by JS) --}}
<div id="stock-row-template" class="d-none">
    <div class="row g-2 align-items-center mb-2 stock-row">
        <div class="col">
            <select name="" class="form-select stock-product" required onchange="onStockProductChange(this)">
                <option value="">{{ __('Select a product') }}</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" data-cost="{{ $product->cost_price ?? 0 }}">
                        {{ $product->name }}{{ $product->sku ? ' (' . $product->sku . ')' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <div class="input-group">
                <span class="input-group-text text-muted small px-2">Cost</span>
                <input type="number" step="0.01" min="0" name="" placeholder="0.00"
                    class="form-control stock-cost text-end" readonly tabindex="-1">
            </div>
        </div>
        <div class="col-3">
            <input type="number" step="0.01" min="0.01" name="" placeholder="{{ __('Quantity') }}"
                class="form-control stock-quantity" required>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-outline-danger btn-sm remove-row-btn" onclick="removeStockRow(this)">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const oldItems = @json($oldItems);
    let stockRowIndex = 0;

    function renderInitialRows() {
        oldItems.forEach(function(item) {
            addStockRow(item.product_id, item.quantity);
        });
    }

    function addStockRow(productId, quantity) {
        const rows = document.getElementById('stock-rows');
        const allRows = rows.querySelectorAll('.stock-row');

        // Validate last row before adding a new empty one
        if (!productId && allRows.length > 0) {
            const lastRow = allRows[allRows.length - 1];
            const lastProduct = lastRow.querySelector('.stock-product').value;
            const lastQty = lastRow.querySelector('.stock-quantity').value;
            if (!lastProduct || !lastQty) {
                Toast.fire({ icon: 'warning', title: '{{ __('Complete the current row first.') }}' });
                return;
            }
        }

        const template = document.getElementById('stock-row-template').querySelector('.stock-row');
        const clone = template.cloneNode(true);
        const index = stockRowIndex++;

        const select = clone.querySelector('.stock-product');
        const input = clone.querySelector('.stock-quantity');

        select.name = `items[${index}][product_id]`;
        input.name = `items[${index}][quantity]`;

        if (productId) {
            select.value = productId;
            // Fill cost price for the restored selection
            const selected = select.options[select.selectedIndex];
            if (selected) {
                const costInput = clone.querySelector('.stock-cost');
                costInput.value = (parseFloat(selected.dataset.cost) || 0).toFixed(2);
            }
        }
        if (quantity) input.value = quantity;

        rows.appendChild(clone);
        updateRemoveButtons();
    }

    function removeStockRow(btn) {
        btn.closest('.stock-row').remove();
        reindexRows();
        updateRemoveButtons();
    }

    function reindexRows() {
        document.querySelectorAll('#stock-rows .stock-row').forEach(function(row, i) {
            row.querySelector('.stock-product').name = `items[${i}][product_id]`;
            row.querySelector('.stock-quantity').name = `items[${i}][quantity]`;
        });
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('#stock-rows .stock-row');
        rows.forEach(function(row) {
            row.querySelector('.remove-row-btn').style.display = rows.length > 1 ? '' : 'none';
        });
    }

    function onStockProductChange(select) {
        const row = select.closest('.stock-row');
        const costInput = row.querySelector('.stock-cost');

        // Check duplicate
        if (select.value) {
            const allSelects = document.querySelectorAll('#stock-rows .stock-product');
            let duplicate = false;
            allSelects.forEach(function(s) {
                if (s !== select && s.value === select.value) duplicate = true;
            });
            if (duplicate) {
                select.value = '';
                costInput.value = '';
                Toast.fire({ icon: 'warning', title: '{{ __('This product is already added.') }}' });
                return;
            }
        }

        // Fill cost price
        const selected = select.options[select.selectedIndex];
        costInput.value = select.value ? (parseFloat(selected.dataset.cost) || 0).toFixed(2) : '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderInitialRows();

        // Auto-open if there were validation errors
        @if($errors->any())
            new bootstrap.Modal(document.getElementById('create-inventory-modal')).show();
        @endif
    });
</script>
@endpush
