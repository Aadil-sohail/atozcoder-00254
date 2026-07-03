@extends('layouts.header')

@section('title', 'New Return')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('New Return') }}</h2>
@endsection

@section('content')

<form method="POST" action="{{ route('returns.store') }}" id="return-form">
    @csrf
    <input type="hidden" name="sale_id" id="sale_id" value="">

    <div class="row g-4">

        {{-- Left: main form --}}
        <div class="col-lg-8">

            {{-- Invoice lookup --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <span class="fw-semibold">{{ __('Find Invoice') }}</span>
                </div>
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label for="invoice_no" class="form-label">{{ __('Invoice No') }}</label>
                            <input id="invoice_no" type="text" class="form-control" autofocus
                                placeholder="{{ __('Enter invoice number and press Enter') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="find-invoice-btn" class="btn btn-outline-primary">
                                <i class="fa-solid fa-magnifying-glass me-1"></i>{{ __('Find') }}
                            </button>
                        </div>
                        <div class="col-md-4">
                            <label for="return_date" class="form-label">{{ __('Return Date') }} <span class="text-danger">*</span></label>
                            <input id="return_date" name="return_date" type="date" required
                                value="{{ old('return_date', date('Y-m-d')) }}"
                                class="form-control @error('return_date') is-invalid @enderror">
                            @error('return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div id="invoice-summary" class="d-none mt-3 pt-3 border-top">
                        <div class="row g-2 small">
                            <div class="col-auto text-muted">{{ __('Customer') }}:</div>
                            <div class="col-auto fw-medium" id="invoice-customer"></div>
                            <div class="col-auto text-muted ms-3">{{ __('Sale Date') }}:</div>
                            <div class="col-auto fw-medium" id="invoice-date"></div>
                        </div>
                    </div>

                    <div id="invoice-not-found" class="d-none mt-3 alert alert-warning py-2 mb-0">
                        {{ __('No sale found for this invoice number.') }}
                    </div>
                </div>
            </div>

            {{-- Items to return --}}
            <div id="return-items-card" class="card shadow-sm border-0 d-none">
                <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                    <span class="fw-semibold">{{ __('Add Item to Return') }}</span>
                    <button type="button" onclick="addReturnRow()" class="btn btn-sm btn-outline-primary">
                        <i class="fa-solid fa-plus me-1"></i>{{ __('Add Item') }}
                    </button>
                </div>
                <div class="card-body p-0">
                    @error('items')
                        <div class="alert alert-warning m-3 py-2 mb-0">{{ $message }}</div>
                    @enderror

                    <div class="px-3 pt-3 pb-1 d-none d-md-block">
                        <div class="row g-2 text-muted small fw-semibold">
                            <div class="col">{{ __('Item') }}</div>
                            <div class="col-2 text-center">{{ __('Qty') }}</div>
                            <div class="col-2 text-center">{{ __('Condition') }}</div>
                            <div class="col-auto" style="width:44px;"></div>
                        </div>
                    </div>

                    <div id="return-rows" class="px-3 pt-2 pb-3"></div>
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
                        <span class="text-muted">{{ __('Items to return') }}</span>
                        <span id="display-count" class="fw-medium">0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">{{ __('Restocked qty (good)') }}</span>
                        <span id="display-good-qty" class="fw-medium text-success">0.00</span>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <button type="submit" id="submit-return-btn" class="btn btn-dark" disabled>
                            <i class="fa-solid fa-check me-1"></i>{{ __('Save Return') }}
                        </button>
                        <a href="{{ route('returns.index') }}" class="btn btn-outline-secondary">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

{{-- Hidden row template (cloned by JS) --}}
<div id="return-row-template" class="d-none">
    <div class="row g-2 align-items-start mb-3 return-row">
        <div class="col">
            <select name="" class="form-select return-item" required onchange="onReturnItemChange(this)">
                <option value="">{{ __('Select item') }}</option>
            </select>
        </div>
        <div class="col-2">
            <input type="number" step="0.01" min="0.01" name="" placeholder="{{ __('Qty') }}"
                class="form-control return-qty text-center" required oninput="recalcReturnRow(this)">
            <small class="remaining-info d-none mt-1" style="font-size:11px;"></small>
        </div>
        <div class="col-2">
            <select name="" class="form-select return-condition">
                <option value="good">{{ __('Good') }}</option>
                <option value="bad">{{ __('Bad') }}</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeReturnRow(this)">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const lookupUrl  = '{{ route('returns.lookup-invoice') }}';
    let currentSale  = null; // { sale_id, invoice_no, customer, sale_date, items: [{sale_item_id, product_id, name, remaining}] }
    let returnRowIndex = 0;

    // ── Find invoice ─────────────────────────────────────────────
    function findInvoice() {
        const invoiceNo = $('#invoice_no').val().trim();
        $('#invoice-not-found').addClass('d-none');

        if (!invoiceNo) return;

        $.post(lookupUrl, {
            _token: '{{ csrf_token() }}',
            invoice_no: invoiceNo,
        }).done(function (res) {
            currentSale = res;

            $('#sale_id').val(res.sale_id);
            $('#invoice-customer').text(res.customer);
            $('#invoice-date').text(res.sale_date);
            $('#invoice-summary').removeClass('d-none');
            $('#return-items-card').removeClass('d-none');

            $('#return-rows').empty();
            returnRowIndex = 0;
            addReturnRow();
        }).fail(function () {
            currentSale = null;
            $('#sale_id').val('');
            $('#invoice-summary').addClass('d-none');
            $('#return-items-card').addClass('d-none');
            $('#invoice-not-found').removeClass('d-none');
            $('#return-rows').empty();
            updateSummary();
        });
    }

    // ── Add row ──────────────────────────────────────────────────
    function addReturnRow() {
        if (!currentSale) return;

        const $rows = $('#return-rows .return-row');
        if ($rows.length > 0) {
            const $last = $rows.last();
            if (!$last.find('.return-item').val() || !$last.find('.return-qty').val()) {
                Toast.fire({ icon: 'warning', title: 'Complete the current row first.' });
                return;
            }
        }

        const $clone = $('#return-row-template .return-row').clone();
        const index  = returnRowIndex++;

        $clone.find('.return-item').attr('name', `items[${index}][sale_item_id]`);
        $clone.find('.return-qty').attr('name', `items[${index}][quantity]`);
        $clone.find('.return-condition').attr('name', `items[${index}][condition]`);

        const $select = $clone.find('.return-item');
        currentSale.items.forEach(function (item) {
            $select.append(
                $('<option>').val(item.sale_item_id).text(item.name + ' — remaining: ' + item.remaining).attr('data-remaining', item.remaining)
            );
        });

        $('#return-rows').append($clone);
        updateSummary();
    }

    // ── Remove row ───────────────────────────────────────────────
    function removeReturnRow(btn) {
        $(btn).closest('.return-row').remove();
        reindexReturnRows();
        updateSummary();
    }

    // ── Reindex name attributes ──────────────────────────────────
    function reindexReturnRows() {
        $('#return-rows .return-row').each(function (i) {
            $(this).find('.return-item').attr('name', `items[${i}][sale_item_id]`);
            $(this).find('.return-qty').attr('name', `items[${i}][quantity]`);
            $(this).find('.return-condition').attr('name', `items[${i}][condition]`);
        });
    }

    // ── Item select change ───────────────────────────────────────
    function onReturnItemChange(select) {
        const $select     = $(select);
        const saleItemId  = $select.val();
        const $row        = $select.closest('.return-row');
        const $qty        = $row.find('.return-qty');
        const $remaining  = $row.find('.remaining-info');

        if (!saleItemId) {
            $qty.removeAttr('max');
            $remaining.addClass('d-none');
            return;
        }

        // Block duplicate item across rows
        let isDuplicate = false;
        $('#return-rows .return-item').each(function () {
            if (this !== select && $(this).val() === saleItemId) isDuplicate = true;
        });
        if (isDuplicate) {
            $select.val('');
            $qty.removeAttr('max').val('');
            $remaining.addClass('d-none');
            Toast.fire({ icon: 'warning', title: 'This item is already added.' });
            return;
        }

        const item = currentSale.items.find(i => i.sale_item_id == saleItemId);
        if (item) {
            $qty.attr('max', item.remaining);
            $remaining
                .text('Remaining: ' + item.remaining)
                .attr('class', 'remaining-info mt-1 d-block ' + (item.remaining > 0 ? 'text-muted' : 'text-danger fw-semibold'));
        }

        recalcReturnRow($qty.get(0));
    }

    // ── Enforce remaining-qty cap ─────────────────────────────────
    function recalcReturnRow(el) {
        const $row       = $(el).closest('.return-row');
        const saleItemId = $row.find('.return-item').val();
        const $qty       = $row.find('.return-qty');
        let   qty        = parseFloat($qty.val()) || 0;

        if (saleItemId && currentSale) {
            const item = currentSale.items.find(i => i.sale_item_id == saleItemId);
            if (item && qty > item.remaining) {
                $qty.val(item.remaining);
                Toast.fire({ icon: 'warning', title: 'Qty cannot exceed remaining returnable quantity (' + item.remaining + ').' });
            }
        }

        updateSummary();
    }

    // ── Summary ────────────────────────────────────────────────────
    function updateSummary() {
        const $rows = $('#return-rows .return-row');
        $('#display-count').text($rows.length);

        let goodQty = 0;
        $rows.each(function () {
            const qty = parseFloat($(this).find('.return-qty').val()) || 0;
            if ($(this).find('.return-condition').val() === 'good') {
                goodQty += qty;
            }
        });
        $('#display-good-qty').text(goodQty.toFixed(2));

        $('#submit-return-btn').prop('disabled', $rows.length === 0);
    }

    $(function () {
        $('#find-invoice-btn').on('click', findInvoice);
        $('#invoice_no').on('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                findInvoice();
            }
        });

        // Enter in a row's qty field moves on to a new row
        $(document).on('keydown', '.return-qty', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addReturnRow();
            }
        });

        // Recalculate summary whenever a row's condition changes
        $(document).on('change', '.return-condition', updateSummary);
    });
</script>
@endpush

@endsection
