@extends('layouts.header')

@section('title', 'New Sale')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('New Sale') }}</h2>
@endsection

@section('content')

<form method="POST" action="{{ route('sales.store') }}" id="sale-form">
    @csrf

    <div class="row g-4">

        {{-- Left: main form --}}
        <div class="col-lg-8">

            {{-- Sale header --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <span class="fw-semibold">{{ __('Sale Details') }}</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="customer_id" class="form-label">{{ __('Customer') }} <span class="text-danger">*</span></label>
                            <select id="customer_id" name="customer_id" required
                                class="form-select @error('customer_id') is-invalid @enderror">
                                <option value="">{{ __('Select customer') }}</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="sale_date" class="form-label">{{ __('Sale Date') }} <span class="text-danger">*</span></label>
                            <input id="sale_date" name="sale_date" type="date" required
                                value="{{ old('sale_date', date('Y-m-d')) }}"
                                class="form-control @error('sale_date') is-invalid @enderror">
                            @error('sale_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="invoice_no" class="form-label">{{ __('Invoice No') }} <span class="text-danger">*</span></label>
                            <input id="invoice_no" name="invoice_no" type="text" required
                                class="form-control @error('invoice_no') is-invalid @enderror">
                            @error('invoice_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Product rows --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                    <span class="fw-semibold">{{ __('Products') }}</span>
                    <button type="button" onclick="addSaleRow()" class="btn btn-sm btn-outline-primary">
                        <i class="fa-solid fa-plus me-1"></i>{{ __('Add Product') }}
                    </button>
                </div>
                <div class="card-body p-0">

                    @error('items')
                        <div class="alert alert-warning m-3 py-2 mb-0">{{ $message }}</div>
                    @enderror

                    {{-- Column headers --}}
                    <div class="px-3 pt-3 pb-1 d-none d-md-block">
                        <div class="row g-2 text-muted small fw-semibold">
                            <div class="col">{{ __('Product') }}</div>
                            <div class="col-2 text-center">{{ __('Qty') }}</div>
                            <div class="col-2 text-center">{{ __('Price') }}</div>
                            <div class="col-2 text-end">{{ __('Subtotal') }}</div>
                            <div class="col-auto" style="width:44px;"></div>
                        </div>
                    </div>

                    <div id="sale-rows" class="px-3 pt-2 pb-3"></div>
                </div>
            </div>
        </div>

        {{-- Right: summary --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top:80px;">
                <div class="card-header bg-white py-3">
                    <span class="fw-semibold">{{ __('Summary') }}</span>
                </div>
                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">{{ __('Subtotal') }}</span>
                        <span id="display-subtotal" class="fw-medium">0.00</span>
                    </div>

                    <div class="mb-3">
                        <label for="discount" class="form-label text-muted">{{ __('Discount (amount)') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">-</span>
                            <input id="discount" name="discount" type="number" step="0.01" min="0"
                                value="{{ old('discount', 0) }}"
                                class="form-control @error('discount') is-invalid @enderror"
                                oninput="recalcAll()">
                            @error('discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-semibold fs-6">{{ __('Total') }}</span>
                        <span id="display-total" class="fw-bold fs-5 text-success">0.00</span>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-dark">
                            <i class="fa-solid fa-check me-1"></i>{{ __('Save Sale') }}
                        </button>
                        <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                            {{ __('Cancel') }}
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</form>

{{-- Hidden row template (cloned by JS) --}}
<div id="sale-row-template" class="d-none">
    <div class="row g-2 align-items-start mb-3 sale-row">
        <div class="col">
            <select name="" class="form-select sale-product" required onchange="onProductChange(this)">
                <option value="">{{ __('Select product') }}</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}"
                        data-price="{{ $product->selling_price }}"
                        data-stock="{{ $product->total_qty }}">
                        {{ $product->name }}{{ $product->sku ? ' (' . $product->sku . ')' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-2">
            <input type="number" step="0.01" min="0.01" name="" placeholder="{{ __('Qty') }}"
                class="form-control sale-qty text-center" required oninput="recalcRow(this)">
            <small class="stock-info d-none mt-1" style="font-size:11px;"></small>
        </div>
        <div class="col-2">
            <input type="number" step="0.01" min="0" name="" placeholder="{{ __('Price') }}"
                class="form-control sale-price text-center" required oninput="recalcRow(this)">
        </div>
        <div class="col-2 text-end pt-2">
            <span class="sale-subtotal fw-medium text-nowrap">0.00</span>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-outline-danger btn-sm remove-sale-row" onclick="removeSaleRow(this)">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const productsData = @json($products->keyBy('id'));
    const oldItems     = @json(old('items', []));
    let saleRowIndex   = 0;

    // ── Add row ──────────────────────────────────────────────────
    function addSaleRow(productId, qty, price) {
        if (!productId) {
            const $rows = $('#sale-rows .sale-row');
            if ($rows.length > 0) {
                const $last = $rows.last();
                if (!$last.find('.sale-product').val() || !$last.find('.sale-qty').val()) {
                    Toast.fire({ icon: 'warning', title: 'Complete the current row first.' });
                    return;
                }
            }
        }

        const $clone = $('#sale-row-template .sale-row').clone();
        const index  = saleRowIndex++;

        $clone.find('.sale-product').attr('name', `items[${index}][product_id]`);
        $clone.find('.sale-qty').attr('name',     `items[${index}][quantity]`);
        $clone.find('.sale-price').attr('name',   `items[${index}][selling_price]`);

        if (productId) {
            $clone.find('.sale-product').val(productId);
            const product       = productsData[productId];
            const resolvedPrice = (price !== undefined && price !== '') ? price : (product ? product.selling_price : '');
            $clone.find('.sale-price').val(resolvedPrice);

            if (product) {
                const stock = Math.max(0, (parseFloat(product.total_qty) || 0) - (parseFloat(product.sold_qty) || 0));
                $clone.find('.stock-info')
                    .text('Available: ' + stock)
                    .attr('class', 'stock-info mt-1 d-block small ' + (stock > 0 ? 'text-muted' : 'text-danger fw-semibold'));
                $clone.find('.sale-qty').attr('max', stock);
            }
        }

        if (qty) $clone.find('.sale-qty').val(qty);

        $('#sale-rows').append($clone);
        updateRemoveButtons();
        recalcAll();
    }

    // ── Remove row ───────────────────────────────────────────────
    function removeSaleRow(btn) {
        $(btn).closest('.sale-row').remove();
        reindexRows();
        updateRemoveButtons();
        recalcAll();
    }

    // ── Reindex name attributes ──────────────────────────────────
    function reindexRows() {
        $('#sale-rows .sale-row').each(function(i) {
            $(this).find('.sale-product').attr('name', `items[${i}][product_id]`);
            $(this).find('.sale-qty').attr('name',     `items[${i}][quantity]`);
            $(this).find('.sale-price').attr('name',   `items[${i}][selling_price]`);
        });
    }

    // ── Show/hide remove button ──────────────────────────────────
    function updateRemoveButtons() {
        const $rows = $('#sale-rows .sale-row');
        $rows.each(function() {
            $(this).find('.remove-sale-row').toggle($rows.length > 1);
        });
    }

    // ── Product select change ────────────────────────────────────
    function onProductChange(select) {
        const $select    = $(select);
        const productId  = $select.val();
        const $row       = $select.closest('.sale-row');
        const $price     = $row.find('.sale-price');
        const $stockInfo = $row.find('.stock-info');
        const $qty       = $row.find('.sale-qty');

        if (!productId) {
            $price.val('');
            $stockInfo.attr('class', 'stock-info d-none');
            recalcRow(select);
            return;
        }

        // Block duplicate
        let isDuplicate = false;
        $('#sale-rows .sale-product').each(function() {
            if (this !== select && $(this).val() === productId) isDuplicate = true;
        });
        if (isDuplicate) {
            $select.val('');
            $price.val('');
            $stockInfo.attr('class', 'stock-info d-none');
            Toast.fire({ icon: 'warning', title: 'This product is already added.' });
            recalcRow(select);
            return;
        }

        // Fill price + show stock
        const product = productsData[productId];
        if (product) {
            $price.val(product.selling_price);
            const stock = Math.max(0, (parseFloat(product.total_qty) || 0) - (parseFloat(product.sold_qty) || 0));
            $stockInfo
                .text('Available: ' + stock)
                .attr('class', 'stock-info mt-1 d-block small ' + (stock > 0 ? 'text-muted' : 'text-danger fw-semibold'));
            $qty.attr('max', stock);
        }

        recalcRow(select);
    }

    // ── Recalc row subtotal + enforce stock cap ──────────────────
    function recalcRow(el) {
        const $row      = $(el).closest('.sale-row');
        const $qty      = $row.find('.sale-qty');
        const productId = $row.find('.sale-product').val();
        let   qty       = parseFloat($qty.val()) || 0;
        const price     = parseFloat($row.find('.sale-price').val()) || 0;

        if (productId && productsData[productId]) {
            const p     = productsData[productId];
            const stock = Math.max(0, (parseFloat(p.total_qty) || 0) - (parseFloat(p.sold_qty) || 0));
            if (qty > stock) {
                $qty.val(stock);
                qty = stock;
                Toast.fire({ icon: 'warning', title: 'Qty cannot exceed available stock (' + stock + ').' });
            }
        }

        $row.find('.sale-subtotal').text((qty * price).toFixed(2));
        recalcAll();
    }

    // ── Recalc grand total ───────────────────────────────────────
    function recalcAll() {
        let subtotal = 0;
        $('#sale-rows .sale-subtotal').each(function() {
            subtotal += parseFloat($(this).text()) || 0;
        });

        const discount = parseFloat($('#discount').val()) || 0;
        const total    = Math.max(0, subtotal - discount);

        $('#display-subtotal').text(subtotal.toFixed(2));
        $('#display-total').text(total.toFixed(2));
    }

    // ── Init ─────────────────────────────────────────────────────
    $(function() {
        if (oldItems.length > 0) {
            $.each(oldItems, function(i, item) {
                addSaleRow(item.product_id, item.quantity, item.selling_price);
            });
        } else {
            addSaleRow();
        }
        recalcAll();
    });
</script>
@endpush

@endsection
