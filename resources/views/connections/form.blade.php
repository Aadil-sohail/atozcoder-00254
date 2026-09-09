@extends('layouts.header')

@php
    // Worked out here rather than inside @json(): Blade reads a directive's
    // arguments by counting brackets, and a "(" inside a string literal — the
    // marketplace label below — would end the directive in the wrong place.
    $storeChoices = $ebayAccounts->map(fn ($account) => [
        'id' => $account->id,
        'label' => $account->store_name,
        'marketplace' => config("ebay.marketplaces.{$account->marketplace_id}.label", $account->marketplace_id),
    ])->values();

    // What the form opens with: what was typed when validation sent it back,
    // otherwise the connection being edited, otherwise one empty row.
    $savedRows = old('items') ?: ($connection?->items->map(fn ($item) => [
        'product_id' => $item->product_id,
        'label' => trim(($item->product?->name ?? '').' - '.($item->product?->sku ?? '')),
        'ebay_account_ids' => $item->stores->pluck('id')->all(),
    ])->values()->all() ?? []);

    $masterChoice = (string) old('master_product_id', $connection->master_product_id ?? '');
@endphp

@section('title', $connection ? 'Edit Connection' : 'New Connection')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">
        {{ $connection ? __('Edit Connection') : __('New Connection') }}
    </h2>
    <p class="text-muted small mb-0">
        {{ __('Pick a product, tick the stores it is listed on, then add the next one.') }}
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
            <div class="card-body">
                <label class="form-label small text-muted mb-1">{{ __('Connection name') }}</label>
                <input type="text" name="name" class="form-control form-control-sm"
                       value="{{ old('name', $connection->name ?? '') }}"
                       placeholder="{{ __('Left blank, the name of the product prices come from is used') }}">
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                <div>
                    <span class="fw-medium">{{ __('Products & the stores they sell on') }}</span>
                    <p class="mb-0 text-muted small">
                        {{ __('One row per product row in the software: one row for a product that sells on several stores, or a row each where the same part came in as separate products. Tick one as the product prices and stock are taken from — that figure is what every store is given.') }}
                    </p>
                </div>
                <button type="button" class="btn btn-outline-dark btn-sm" id="add-row">
                    <i class="fa-solid fa-plus me-1"></i>{{ __('Add product') }}
                </button>
            </div>

            <div class="card-body">
                @if ($ebayAccounts->isEmpty())
                    <p class="text-muted small mb-2">{{ __('No eBay store is connected yet.') }}</p>
                @endif

                <div id="rows"></div>
            </div>

            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                <span class="small text-muted" id="summary">{{ __('Pick the product this connection is for.') }}</span>
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

    function debounce(fn, wait) {
        let timer;
        return function () {
            clearTimeout(timer);
            timer = setTimeout(fn, wait);
        };
    }

    function money(value) {
        return Number(value || 0).toFixed(2);
    }

    function storeTicks(index, checked) {
        if (! stores.length) {
            return `<span class="text-muted small">{{ __('No stores connected') }}</span>`;
        }

        return stores.map(store => `
            <div class="form-check form-check-inline">
                <input class="form-check-input row-store" type="checkbox"
                       name="items[${index}][ebay_account_ids][]" value="${store.id}"
                       id="store-${index}-${store.id}"
                       ${(checked ?? []).map(String).includes(String(store.id)) ? 'checked' : ''}>
                <label class="form-check-label small" for="store-${index}-${store.id}">
                    <i class="fa-brands fa-ebay me-1"></i>${escapeHtml(store.label)}
                </label>
            </div>
        `).join('');
    }

    function addRow(prefill) {
        const index = nextIndex++;
        const row = document.createElement('div');

        row.className = 'connection-row border rounded p-2 mb-2';
        row.dataset.index = index;
        row.innerHTML = `
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small text-muted mb-1">{{ __('Product') }}</label>
                    <select name="items[${index}][product_id]" class="form-select form-select-sm row-product" required>
                        ${prefill?.product_id
                            ? `<option value="${prefill.product_id}" selected>${escapeHtml(prefill.label || ('#' + prefill.product_id))}</option>`
                            : '<option value="">{{ __('Choose a product') }}</option>'}
                    </select>
                    <input type="text" class="form-control form-control-sm mt-1 row-search"
                           placeholder="{{ __('Search by name, SKU or listing id…') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">{{ __('Listed on') }}</label>
                    <div class="row-stores">${storeTicks(index, prefill?.ebay_account_ids)}</div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input row-master" type="radio" name="master_product_id"
                               value="${prefill?.product_id ?? ''}" id="master-${index}"
                               ${prefill?.product_id && String(prefill.product_id) === master ? 'checked' : ''}>
                        <label class="form-check-label small" for="master-${index}">
                            {{ __('Prices & stock') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger row-remove" title="{{ __('Remove') }}">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
            <div class="small text-muted mt-1 row-detail"></div>
        `;

        rowsBox.appendChild(row);

        const product = row.querySelector('.row-product');
        const search = row.querySelector('.row-search');
        const masterBox = row.querySelector('.row-master');

        product.addEventListener('change', function () {
            // The radio stands for whichever product the row holds now, so a
            // row that was the master keeps that as its product changes.
            const wasMaster = masterBox.checked;

            masterBox.value = product.value;
            masterBox.checked = wasMaster && product.value !== '';

            tickOwnStores(row);
            showDetail(row);
            refresh();
        });

        search.addEventListener('input', debounce(() => loadProducts(row, search.value), 350));

        row.querySelector('.row-remove').addEventListener('click', function () {
            row.remove();
            refresh();
        });

        loadProducts(row);
        refresh();

        return row;
    }

    async function loadProducts(row, search) {
        const product = row.querySelector('.row-product');
        const chosen = product.value;
        const chosenLabel = product.options[product.selectedIndex]?.textContent ?? '';

        const url = new URL(productsUrl, window.location.origin);
        if (search) url.searchParams.set('q', search);
        if (connectionId) url.searchParams.set('connection', connectionId);

        let items = [];

        try {
            const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
            items = (await response.json()).products ?? [];
        } catch (error) {
            product.innerHTML = '<option value="">{{ __('Could not load products') }}</option>';
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
                data-stores="${escapeHtml((item.stores ?? []).join(','))}"
                ${item.connected_to ? 'disabled' : ''}
                ${String(item.id) === String(chosen) ? 'selected' : ''}>${escapeHtml(label)}</option>`);
        });

        product.innerHTML = options.join('');
        showDetail(row);
        refresh();
    }

    /**
     * A product already carries its eBay listings, so the stores it is on are
     * ticked for the user rather than left to be remembered. Only on a fresh
     * pick: a tick taken off by hand stays off.
     */
    function tickOwnStores(row) {
        const option = row.querySelector('.row-product').selectedOptions[0];
        const own = (option?.dataset.stores || '').split(',').filter(Boolean);

        row.querySelectorAll('.row-store').forEach(box => {
            box.checked = own.includes(box.value);
        });
    }

    function showDetail(row) {
        const option = row.querySelector('.row-product').selectedOptions[0];
        const detail = row.querySelector('.row-detail');

        if (! option?.value || option.dataset.selling === undefined) {
            detail.textContent = '';
            return;
        }

        detail.textContent = `{{ __('Own price') }}: ${money(option.dataset.selling)}`
            + ` · {{ __('cost') }}: ${money(option.dataset.cost)}`
            + ` · {{ __('own stock') }}: ${money(option.dataset.stock)}`;
    }

    function refresh() {
        const rows = Array.from(rowsBox.querySelectorAll('.connection-row'));

        // The same product twice would be one row wasted and a confusing
        // connection, so a product picked in one row is closed off in the rest.
        const picked = rows.map(row => row.querySelector('.row-product').value).filter(Boolean);

        rows.forEach(row => {
            const product = row.querySelector('.row-product');

            Array.from(product.options).forEach(option => {
                if (! option.value || option.value === product.value) return;
                if (picked.includes(option.value)) option.disabled = true;
            });
        });

        const chosen = picked.length;
        const storeCount = new Set(
            Array.from(rowsBox.querySelectorAll('.row-store:checked')).map(box => box.value)
        ).size;

        // With one product there is nothing to choose between, so the row that
        // holds it is the one prices and stock come from.
        if (chosen === 1 && ! rowsBox.querySelector('.row-master:checked')) {
            const only = rows.find(row => row.querySelector('.row-product').value);

            if (only) only.querySelector('.row-master').checked = true;
        }

        const masterBox = rowsBox.querySelector('.row-master:checked');

        // Worth connecting two ways: several product rows that are the same
        // part, or one product row selling on more than one store.
        const worthIt = chosen >= 2 || (chosen === 1 && storeCount >= 2);

        saveButton.disabled = ! (worthIt && masterBox && masterBox.value);

        if (chosen === 0) {
            summary.textContent = '{{ __('Pick the product this connection is for.') }}';
        } else if (! worthIt) {
            summary.textContent = '{{ __('Tick the stores it sells on, or add the other product row for the same part.') }}';
        } else if (! masterBox || ! masterBox.value) {
            summary.textContent = '{{ __('Now tick which product the prices and stock come from.') }}';
        } else {
            const option = masterBox.closest('.connection-row').querySelector('.row-product').selectedOptions[0];

            summary.textContent = `{{ __('Shown as one product') }}: `
                + `{{ __('price') }} ${money(option?.dataset.selling)}, `
                + `{{ __('stock') }} ${money(option?.dataset.stock)} `
                + `— {{ __('shared across') }} ${storeCount} {{ __('stores') }}.`;
        }
    }

    document.getElementById('add-row').addEventListener('click', () => addRow(null));

    rowsBox.addEventListener('change', function (event) {
        if (event.target.classList.contains('row-master') || event.target.classList.contains('row-store')) {
            refresh();
        }
    });

    if (saved.length) {
        saved.forEach(item => addRow(item));
    } else {
        addRow(null);
    }
});
</script>
@endpush
