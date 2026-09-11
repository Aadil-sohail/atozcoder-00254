@extends('layouts.header')

@php
    // Worked out here rather than inside @json(): Blade reads a directive's
    // arguments by counting brackets, and a "(" inside a string literal — the
    // marketplace label below — would end the directive in the wrong place.
    $storeChoices = $ebayAccounts->map(fn ($account) => [
        'id' => $account->id,
        'label' => $account->store_name.' ('.config("ebay.marketplaces.{$account->marketplace_id}.label", $account->marketplace_id).')',
    ])->values();

    // One row per store. A product listed on two stores was saved as one
    // member carrying both, so it comes back out as a row for each.
    $savedRows = old('items') ?: ($connection?->items->flatMap(function ($item) {
        $label = trim(($item->product?->name ?? '').' - '.($item->product?->sku ?? ''));
        $stores = $item->stores->pluck('id');

        return $stores->isEmpty()
            ? [['ebay_account_id' => null, 'product_id' => $item->product_id, 'label' => $label]]
            : $stores->map(fn ($storeId) => [
                'ebay_account_id' => $storeId,
                'product_id' => $item->product_id,
                'label' => $label,
            ])->all();
    })->values()->all() ?? []);

    // The marked row is named by its store: with one product row serving two
    // stores, the product cannot tell the two rows apart.
    $masterChoice = (string) old('master_ebay_account_id', $connection->master_ebay_account_id ?? '');
@endphp

@push('styles')
<style>
    /* Select2's own control, trimmed to the size of a form-select-sm. */
    #connection-form .select2-container--bootstrap-5 .select2-selection {
        min-height: calc(1.5em + 0.5rem + 2px);
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    #connection-form .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        padding: 0;
        line-height: 1.5;
    }
</style>
@endpush

@section('title', $connection ? 'Edit Connection' : 'New Connection')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">
        {{ $connection ? __('Edit Connection') : __('New Connection') }}
    </h2>
    <p class="text-muted small mb-0">
        {{ __('Pick the store, then the product on it. Do that for every store the same part sells on, and say whose price to use.') }}
    </p>
@endsection

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" id="connection-form"
          action="{{ $connection ? route('connections.update', $connection) : route('connections.store') }}">
        @csrf
        @if ($connection)
            @method('PUT')
        @endif


        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                <div>
                    <span class="fw-medium">{{ __('The same part, store by store') }}</span>
                    <p class="mb-0 text-muted small">
                        {{ __('One row per store. Mark the one store whose price and stock stand for all of them — that is the figure every store is given.') }}
                    </p>
                </div>
                <button type="button" class="btn btn-outline-dark btn-sm" id="add-row">
                    <i class="fa-solid fa-plus me-1"></i>{{ __('Add store') }}
                </button>
            </div>

            <div class="card-body">
                @if ($ebayAccounts->isEmpty())
                    <p class="text-muted small mb-2">{{ __('No eBay store is connected yet.') }}</p>
                @endif

                <div id="rows"></div>
            </div>

            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                <span class="small text-muted" id="summary">{{ __('Pick a store and the product on it.') }}</span>
                <div class="d-flex gap-2">
                    <a href="{{ route('connections.index') }}" class="btn btn-outline-secondary btn-sm">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-dark btn-sm" id="save-connection" disabled>
                        <i class="fa-solid fa-link me-1"></i>{{ $connection ? __('Save connection') : __('Create connection') }}
                    </button>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const stores = @json($storeChoices);
    const saved = @json($savedRows);
    const master = @json($masterChoice);
    const productsUrl = @json(route('connections.store-products'));
    const connectionId = @json($connection?->id);

    const rowsBox = document.getElementById('rows');
    const summary = document.getElementById('summary');
    const saveButton = document.getElementById('save-connection');

    let nextIndex = 0;

    function escapeHtml(value) {
        const box = document.createElement('span');
        box.textContent = value ?? '';
        return box.innerHTML;
    }

    function money(value) {
        return Number(value || 0).toFixed(2);
    }

    function searchable(select) {
        // No placeholder given on purpose: the empty first option already
        // reads "Choose a store" / "Choose a product", and a placeholder would
        // paint over it.
        $(select).select2({
            theme: 'bootstrap-5',
            width: '100%',
        });
    }

    function storeOptions(selected) {
        return ['<option value="">{{ __('Choose a store') }}</option>']
            .concat(stores.map(store =>
                `<option value="${store.id}" ${String(store.id) === String(selected ?? '') ? 'selected' : ''}>${escapeHtml(store.label)}</option>`))
            .join('');
    }

    function addRow(prefill) {
        const index = nextIndex++;
        const row = document.createElement('div');

        row.className = 'connection-row border rounded p-2 mb-2';
        row.dataset.index = index;
        row.innerHTML = `
            <div class="row g-2 align-items-start">
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">{{ __('Store') }}</label>
                    <select name="items[${index}][ebay_account_id]" class="form-select form-select-sm row-store">
                        ${storeOptions(prefill?.ebay_account_id)}
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label small text-muted mb-1">{{ __('Product on that store') }}</label>
                    <select name="items[${index}][product_id]" class="form-select form-select-sm row-product">
                        ${prefill?.product_id
                            ? `<option value="${prefill.product_id}" selected>${escapeHtml(prefill.label || ('#' + prefill.product_id))}</option>`
                            : '<option value="">{{ __('Choose the store first') }}</option>'}
                    </select>
                    <div class="small text-muted mt-1 row-detail"></div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1 d-none d-md-block">&nbsp;</label>
                    <div class="form-check">
                        <input class="form-check-input row-master" type="radio" name="master_ebay_account_id"
                               value="${prefill?.ebay_account_id ?? ''}" id="master-${index}"
                               ${prefill?.ebay_account_id && String(prefill.ebay_account_id) === master ? 'checked' : ''}>
                        <label class="form-check-label small" for="master-${index}">
                            {{ __("Use this store's price & stock") }}
                        </label>
                    </div>
                </div>
                <div class="col-md-1 text-end">
                    <label class="form-label small text-muted mb-1 d-none d-md-block">&nbsp;</label>
                    <button type="button" class="btn btn-sm btn-outline-danger row-remove" title="{{ __('Remove') }}">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
        `;

        rowsBox.appendChild(row);

        const store = row.querySelector('.row-store');
        const product = row.querySelector('.row-product');
        const masterBox = row.querySelector('.row-master');

        searchable(store);
        searchable(product);

        $(store).on('change', function () {
            // A different store means a different shelf of products, so
            // whatever was picked here no longer applies.
            product.innerHTML = '<option value="">{{ __('Loading…') }}</option>';
            $(product).trigger('change.select2');

            // The radio stands for this row's store, so it moves with it.
            const wasMaster = masterBox.checked;

            masterBox.value = store.value;
            masterBox.checked = wasMaster && store.value !== '';

            refresh();
            loadProducts(row);
        });

        $(product).on('change', function () {
            showDetail(row);
            refresh();
        });

        row.querySelector('.row-remove').addEventListener('click', function () {
            $(store).select2('destroy');
            $(product).select2('destroy');

            row.remove();
            refresh();
        });

        if (prefill?.ebay_account_id || prefill?.product_id) {
            loadProducts(row);
        }

        refresh();

        return row;
    }

    async function loadProducts(row) {
        const store = row.querySelector('.row-store').value;
        const product = row.querySelector('.row-product');
        const chosen = product.value;
        const chosenLabel = product.options[product.selectedIndex]?.textContent ?? '';

        if (! store) {
            product.innerHTML = '<option value="">{{ __('Choose the store first') }}</option>';
            $(product).trigger('change.select2');
            refresh();
            return;
        }

        const url = new URL(productsUrl, window.location.origin);
        url.searchParams.set('store', store);
        if (connectionId) url.searchParams.set('connection', connectionId);

        let items = [];

        try {
            const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
            items = (await response.json()).products ?? [];
        } catch (error) {
            product.innerHTML = '<option value="">{{ __('Could not load products') }}</option>';
            $(product).trigger('change.select2');
            return;
        }

        const options = ['<option value="">{{ __('Choose a product') }}</option>'];

        // Whatever was already picked stays available, even when a search
        // would not have listed it.
        if (chosen && ! items.some(item => String(item.id) === String(chosen))) {
            options.push(`<option value="${chosen}" selected>${escapeHtml(chosenLabel.trim())}</option>`);
        }

        items.forEach(item => {
            const bits = [item.name];
            if (item.listing_id) bits.push(item.listing_id);
            else if (item.sku) bits.push(item.sku);

            const label = bits.join(' — ') + (item.connected_to ? ` (${item.connected_to})` : '');

            options.push(`<option value="${item.id}"
                data-stock="${item.stock ?? 0}"
                data-selling="${item.selling_price ?? ''}"
                data-cost="${item.cost_price ?? ''}"
                ${item.connected_to ? 'disabled' : ''}
                ${String(item.id) === String(chosen) ? 'selected' : ''}>${escapeHtml(label)}</option>`);
        });

        product.innerHTML = options.join('');
        $(product).trigger('change.select2');

        showDetail(row);
        refresh();
    }

    function showDetail(row) {
        const option = row.querySelector('.row-product').selectedOptions[0];
        const detail = row.querySelector('.row-detail');

        if (! option?.value || option.dataset.selling === undefined) {
            detail.textContent = '';
            return;
        }

        detail.textContent = `{{ __('This store:') }} {{ __('price') }} ${money(option.dataset.selling)}`
            + ` · {{ __('cost') }} ${money(option.dataset.cost)}`
            + ` · {{ __('stock') }} ${money(option.dataset.stock)}`;
    }

    function refresh() {
        const rows = Array.from(rowsBox.querySelectorAll('.connection-row'));

        // One row per store: a store already spoken for is closed off in the
        // others, so the same listing cannot be added twice.
        const usedStores = rows.map(row => row.querySelector('.row-store').value).filter(Boolean);

        rows.forEach(row => {
            const store = row.querySelector('.row-store');

            Array.from(store.options).forEach(option => {
                option.disabled = Boolean(option.value)
                    && option.value !== store.value
                    && usedStores.includes(option.value);
            });
        });

        const filled = rows.filter(row => row.querySelector('.row-store').value && row.querySelector('.row-product').value);

        // With one row there is nothing to choose between, so it is the one
        // the price comes from.
        if (filled.length === 1 && ! rowsBox.querySelector('.row-master:checked')) {
            filled[0].querySelector('.row-master').checked = true;
        }

        const masterBox = rowsBox.querySelector('.row-master:checked');

        saveButton.disabled = ! (filled.length >= 2 && masterBox && masterBox.value);

        if (filled.length === 0) {
            summary.textContent = '{{ __('Pick a store and the product on it.') }}';
        } else if (filled.length < 2) {
            summary.textContent = '{{ __('Now add the next store and the same part on it.') }}';
        } else if (! masterBox || ! masterBox.value) {
            summary.textContent = '{{ __("Now mark the store whose price and stock to use.") }}';
        } else {
            const option = masterBox.closest('.connection-row').querySelector('.row-product').selectedOptions[0];
            const storeName = masterBox.closest('.connection-row').querySelector('.row-store').selectedOptions[0]?.textContent ?? '';
            const products = new Set(filled.map(row => row.querySelector('.row-product').value)).size;

            summary.textContent = `{{ __('Shown as one product') }}: `
                + `{{ __('price') }} ${money(option?.dataset.selling)}, `
                + `{{ __('stock') }} ${money(option?.dataset.stock)} {{ __('from') }} ${storeName.trim()} `
                + `— {{ __('shared across') }} ${filled.length} {{ __('stores') }}`
                + (products > 1 ? `, ${products} {{ __('product rows joined') }}.` : '.');
        }
    }

    document.getElementById('add-row').addEventListener('click', () => addRow(null));

    rowsBox.addEventListener('change', function (event) {
        if (event.target.classList.contains('row-master')) refresh();
    });

    if (saved.length) {
        saved.forEach(item => addRow(item));
    } else {
        addRow(null);
        addRow(null);
    }
});
</script>
@endpush
