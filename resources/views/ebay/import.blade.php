@extends('layouts.header')

@section('title', 'Select Products to Import')

@section('header')
    <h1 class="h3 mb-0">{{ __('Import from eBay') }}</h1>
    <p class="text-muted small mb-0">
        {{ __('Listed on') }} <strong>{{ $account->store_name }}</strong>
        ({{ config("ebay.marketplaces.{$account->marketplace_id}.label", $account->marketplace_id) }})
    </p>
@endsection

@section('content')
    <div class="card shadow-sm">
        <form method="POST" action="{{ route('ebay.import.save', $account) }}" id="ebay-import-form">
            @csrf

            <div class="card-header d-flex flex-wrap gap-2 align-items-center justify-content-between">
                <div class="small text-muted">
                    {{ __('Tick the products you want. Only the ticked ones are added  the rest are thrown away.') }}
                    <span class="d-block">
                        <strong id="ebay-selected-count">0</strong>
                        {{ __('of') }} {{ $items->count() }} {{ __('selected') }}
                    </span>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="ebay-select-all">
                        {{ __('Select all') }}
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="ebay-select-none">
                        {{ __('Clear') }}
                    </button>
                    <button type="submit" class="btn btn-dark btn-sm" id="ebay-save-selected" disabled>
                        <i class="fa-solid fa-floppy-disk me-1"></i>{{ __('Import Selected Products') }}
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle" id="ebay-import-table">
                        <thead>
                            <tr>
                                <th style="width:2.5rem" data-orderable="false">
                                    <input type="checkbox" class="form-check-input" id="ebay-check-all"
                                           title="{{ __('Select / clear every product on every page') }}">
                                </th>
                                <th style="width:4rem" data-orderable="false">{{ __('Image') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('SKU') }}</th>
                                <th>{{ __('Condition') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th>{{ __('Qty') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input ebay-import-check"
                                               name="item_ids[]" value="{{ $item->id }}"
                                               @checked(! $item->already_in_software)>
                                    </td>
                                    <td>
                                        @if ($url = ($item->image_urls[0] ?? null))
                                            <img src="{{ $url }}" alt="" class="rounded"
                                                 style="width:40px;height:40px;object-fit:cover" loading="lazy">
                                        @else
                                            <span class="text-muted"><i class="fa-regular fa-image"></i></span>
                                        @endif
                                    </td>
                                    <td>{{ $item->title }}</td>
                                    <td class="text-muted small">{{ $item->sku }}</td>
                                    <td class="small">
                                        {{ config("ebay.conditions.{$item->condition}", $item->condition) }}
                                    </td>
                                    <td>{{ $item->price === null ? '—' : number_format((float) $item->price, 2) }}</td>
                                    <td>{{ (int) $item->quantity }}</td>
                                    <td>
                                        @if ($item->already_in_software)
                                            <span class="badge text-bg-light border">{{ __('Already here') }}</span>
                                        @else
                                            <span class="badge text-bg-success-subtle text-success border border-success-subtle">
                                                {{ __('New') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        <div class="card-footer d-flex justify-content-between align-items-center">
            <span class="small text-muted">
                {{ __('This list is temporary and is cleared once you save or discard it.') }}
            </span>

            <form method="POST" action="{{ route('ebay.import.discard', $account) }}"
                  onsubmit="return confirm('{{ __('Throw this list away without importing anything?') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="fa-regular fa-trash-can me-1"></i>{{ __('Discard') }}
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = new DataTable('#ebay-import-table', {
        pageLength: 25,
        order: [],
        columnDefs: [{ targets: [0, 1], orderable: false, searchable: false }],
    });

    const form        = document.getElementById('ebay-import-form');
    const counter     = document.getElementById('ebay-selected-count');
    const saveButton  = document.getElementById('ebay-save-selected');
    const masterCheck = document.getElementById('ebay-check-all');

    // DataTables keeps only the current page's rows in the document, so a plain
    // querySelectorAll misses ticks made on the other pages. Ask the API for the
    // row nodes instead  it hands back the detached ones too.
    function boxes(searchedOnly) {
        const rows = searchedOnly
            ? table.rows({ search: 'applied' }).nodes()
            : table.rows().nodes();

        return rows.toArray()
            .map(row => row.querySelector('.ebay-import-check'))
            .filter(Boolean);
    }

    function refresh() {
        const all      = boxes(false);
        const selected = all.filter(box => box.checked).length;
        const shown    = boxes(true);
        const onScreen = shown.filter(box => box.checked).length;

        counter.textContent   = selected;
        saveButton.disabled   = selected === 0;

        // Master reflects the rows the current search covers (all of them when
        // the search box is empty), across every page  not just this one.
        masterCheck.checked       = shown.length > 0 && onScreen === shown.length;
        masterCheck.indeterminate = onScreen > 0 && onScreen < shown.length;
    }

    function setAll(checked, searchedOnly) {
        boxes(searchedOnly).forEach(box => { box.checked = checked; });
        refresh();
    }

    form.addEventListener('change', function (event) {
        if (event.target.classList.contains('ebay-import-check')) {
            refresh();
        }
    });

    masterCheck.addEventListener('change', function () {
        setAll(masterCheck.checked, true);
    });

    document.getElementById('ebay-select-all').addEventListener('click', () => setAll(true, false));
    document.getElementById('ebay-select-none').addEventListener('click', () => setAll(false, false));

    // Detached rows never reach the POST body, so mirror their ticks into
    // hidden inputs the moment the form is submitted.
    form.addEventListener('submit', function () {
        form.querySelectorAll('.ebay-import-hidden').forEach(input => input.remove());

        boxes(false)
            .filter(box => box.checked && ! box.isConnected)
            .forEach(function (box) {
                const hidden = document.createElement('input');
                hidden.type      = 'hidden';
                hidden.name      = 'item_ids[]';
                hidden.value     = box.value;
                hidden.className = 'ebay-import-hidden';
                form.appendChild(hidden);
            });
    });

    // Redraws (paging, search, sorting) re-attach a different slice of rows.
    table.on('draw', refresh);

    refresh();
});
</script>
@endpush
