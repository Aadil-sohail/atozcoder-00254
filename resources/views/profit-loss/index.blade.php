@extends('layouts.header')

@section('title', 'Profit & Loss')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('Profit & Loss') }}</h2>
@endsection

@push('styles')
<style>
    .pl-tile {
        background: #fff;
        border: 1px solid rgba(11, 11, 11, .09);
        border-radius: 12px;
        padding: 16px 18px;
        height: 100%;
    }
    .pl-icon {
        width: 34px; height: 34px; border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px;
    }
    .pl-label { font-size: 12px; font-weight: 500; color: #898781; margin: 12px 0 2px; }
    .pl-value { font-size: 26px; line-height: 1.15; font-weight: 600; color: #0b0b0b; margin: 0; }
    .pl-meta { font-size: 12px; color: #52514e; margin: 6px 0 0; }
    .pl-good { color: #006300 !important; }
    .pl-bad { color: #d03b3b !important; }
</style>
@endpush

@section('content')

@php
    /* Money for a tile: exact where it matters, compact once it stops fitting. */
    $money = function ($n) {
        $n = (float) $n;
        if (abs($n) >= 1000000) return number_format($n / 1000000, 2) . 'M';
        if (abs($n) >= 100000)  return number_format($n / 1000, 1) . 'K';
        return number_format($n, 2);
    };
    $trim = fn ($n) => rtrim(rtrim(number_format((float) $n, 2), '0'), '.');

    $profit = (float) $totals->profit;
    $margin = (float) $totals->revenue != 0.0 ? $profit / (float) $totals->revenue * 100 : null;

    $periodLabel = match (true) {
        request()->filled('date_from') && request()->filled('date_to') =>
            \Carbon\Carbon::parse(request('date_from'))->format('M d, Y').' – '.\Carbon\Carbon::parse(request('date_to'))->format('M d, Y'),
        request()->filled('date_from') => __('From').' '.\Carbon\Carbon::parse(request('date_from'))->format('M d, Y'),
        request()->filled('date_to') => __('Up to').' '.\Carbon\Carbon::parse(request('date_to'))->format('M d, Y'),
        default => __('All time'),
    };
@endphp

{{-- ── KPI row: the same numbers the grid adds up, summed in SQL ────────── --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-xl-3">
        <div class="pl-tile">
            <div class="pl-icon" style="background:rgba(42,120,214,.12); color:#2a78d6;">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
            <p class="pl-label">{{ __('Revenue') }}</p>
            <p class="pl-value">{{ $money($totals->revenue) }}</p>
            <p class="pl-meta">{{ $trim($totals->units) }} {{ __('units sold') }}</p>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="pl-tile">
            <div class="pl-icon" style="background:rgba(235,104,52,.12); color:#eb6834;">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <p class="pl-label">{{ __('Cost of goods sold') }}</p>
            <p class="pl-value">{{ $money($totals->cogs) }}</p>
            <p class="pl-meta">{{ __('At each product\'s cost price') }}</p>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="pl-tile">
            <div class="pl-icon" style="background:{{ $profit < 0 ? 'rgba(208,59,59,.12)' : 'rgba(27,175,122,.14)' }};
                color:{{ $profit < 0 ? '#d03b3b' : '#12805a' }};">
                <i class="fa-solid fa-arrow-trend-{{ $profit < 0 ? 'down' : 'up' }}"></i>
            </div>
            <p class="pl-label">{{ $profit < 0 ? __('Net loss') : __('Net profit') }}</p>
            <p class="pl-value {{ $profit < 0 ? 'pl-bad' : 'pl-good' }}">{{ $money($profit) }}</p>
            <p class="pl-meta">{{ __('Revenue less cost of goods') }}</p>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="pl-tile">
            <div class="pl-icon" style="background:rgba(237,161,0,.14); color:#a86f00;">
                <i class="fa-solid fa-percent"></i>
            </div>
            <p class="pl-label">{{ __('Margin') }}</p>
            <p class="pl-value">{{ $margin === null ? '—' : number_format($margin, 1).'%' }}</p>
            <p class="pl-meta">
                {{ $totals->products }} {{ Str::plural('product', (int) $totals->products) }}
                @if ($totals->loss_makers > 0)
                    · <span class="pl-bad">{{ $totals->loss_makers }} {{ __('at a loss') }}</span>
                @endif
            </p>
        </div>
    </div>
</div>

{{-- ── Filters ─────────────────────────────────────────────────────────────
     A normal GET submit rather than an in-place reload: the tiles above are
     summed for the same filters, so both have to be rebuilt together or they
     would end up describing different periods. --}}
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body py-3">
        <form id="profit-loss-filters" method="GET" action="{{ route('profit-loss.index') }}" class="row g-2 align-items-end">

            <div class="col-md-2">
                <label for="date_from" class="form-label small text-muted mb-1">{{ __('Sold From') }}</label>
                <input id="date_from" name="date_from" type="date" value="{{ request('date_from') }}"
                    class="form-control form-control-sm">
            </div>

            <div class="col-md-2">
                <label for="date_to" class="form-label small text-muted mb-1">{{ __('Sold To') }}</label>
                <input id="date_to" name="date_to" type="date" value="{{ request('date_to') }}"
                    class="form-control form-control-sm">
            </div>

            <div class="col-md-2">
                <label for="category_id" class="form-label small text-muted mb-1">{{ __('Category') }}</label>
                <select id="category_id" name="category_id" class="form-select form-select-sm">
                    <option value="">{{ __('All categories') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="subcategory_id" class="form-label small text-muted mb-1">{{ __('Sub Category') }}</label>
                <select id="subcategory_id" name="subcategory_id" class="form-select form-select-sm">
                    <option value="">{{ __('All sub categories') }}</option>
                    @foreach ($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}" data-category="{{ $subcategory->category_id }}"
                            @selected(request('subcategory_id') == $subcategory->id)>
                            {{ $subcategory->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="result" class="form-label small text-muted mb-1">{{ __('Result') }}</label>
                <select id="result" name="result" class="form-select form-select-sm">
                    <option value="">{{ __('Profit & loss') }}</option>
                    <option value="profit" @selected(request('result') === 'profit')>{{ __('In profit only') }}</option>
                    <option value="loss" @selected(request('result') === 'loss')>{{ __('At a loss only') }}</option>
                </select>
            </div>

            <div class="col-md-2">
                <label for="show" class="form-label small text-muted mb-1">{{ __('Products') }}</label>
                <select id="show" name="show" class="form-select form-select-sm">
                    <option value="">{{ __('Sold in period') }}</option>
                    <option value="all" @selected(request('show') === 'all')>{{ __('Include unsold') }}</option>
                </select>
            </div>

            <div class="col-12 d-flex gap-2 pt-1">
                <button type="submit" class="btn btn-dark btn-sm">
                    <i class="fa-solid fa-filter me-1"></i>{{ __('Apply') }}
                </button>
                <a href="{{ route('profit-loss.index') }}" class="btn btn-outline-secondary btn-sm">
                    {{ __('Reset') }}
                </a>
            </div>

        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <p class="mb-0 text-muted small">
            {{ __('Profit per product: average cost against the price it actually sold at, net of returns and discounts.') }}
        </p>
        <span class="text-muted" style="font-size:12px;" id="profit-loss-count">
            {{ $totals->products }} {{ Str::plural('product', (int) $totals->products) }} · {{ $periodLabel }}
        </span>
    </div>

    <div class="table-responsive">
        <table id="profit-loss-table" class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>{{ __('Product') }}</th>
                    <th>{{ __('Category') }}</th>
                    <th>{{ __('Cost Price') }}</th>
                    <th>{{ __('Avg Sale Price') }}</th>
                    <th>{{ __('Units Sold') }}</th>
                    <th>{{ __('Revenue') }}</th>
                    <th>{{ __('Cost of Goods') }}</th>
                    <th>{{ __('Profit / Loss') }}</th>
                    <th>{{ __('Margin') }}</th>
                    <th class="no-sort">{{ __('Sales') }}</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

{{-- Per-product sale breakdown, loaded on demand. --}}
<div class="modal fade" id="profit-breakdown-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="fa-solid fa-list-ul me-2"></i>{{ __('Sales Breakdown') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="profit-breakdown-body">
                <div class="text-center text-muted py-5">
                    <span class="spinner-border spinner-border-sm me-2"></span>{{ __('Loading…') }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Rows are fetched a page at a time — see App\Support\ServerTable. The
    // filter form's values are sent with every draw, so paging and filtering
    // stay in step with the totals rendered above.
    $(function () {
        const filters = document.getElementById('profit-loss-filters');

        const table = serverTable('#profit-loss-table', {
            url: @json(route('profit-loss.data')),
            columns: [
                { data: 'product' },
                { data: 'category' },
                { data: 'cost_price', searchable: false },
                { data: 'avg_price', searchable: false },
                { data: 'qty_net', searchable: false },
                { data: 'revenue', searchable: false },
                { data: 'cogs', searchable: false },
                { data: 'profit', searchable: false },
                { data: 'margin', searchable: false },
                { data: 'actions', orderable: false, searchable: false },
            ],
            // Biggest earners first; sorting the column flips to the losses.
            order: [[7, 'desc']],
            ajax: {
                data: function (params) {
                    new FormData(filters).forEach(function (value, key) {
                        params[key] = value;
                    });
                },
            },
        });

        // Sub categories belong to a category, so the list narrows with it.
        const categorySelect = document.getElementById('category_id');
        const subcategorySelect = document.getElementById('subcategory_id');

        function syncSubcategories() {
            const category = categorySelect.value;

            Array.from(subcategorySelect.options).forEach(function (option) {
                if (!option.value) return;
                const matches = !category || option.dataset.category === category;
                option.hidden = !matches;
                if (!matches && option.selected) subcategorySelect.value = '';
            });
        }

        categorySelect.addEventListener('change', syncSubcategories);
        syncSubcategories();
    });

    // The breakdown is only fetched when a row is opened — one product's sale
    // lines are cheap, all of them on every draw would not be.
    function openProfitBreakdown(productId, button) {
        const modalEl = document.getElementById('profit-breakdown-modal');
        const body = document.getElementById('profit-breakdown-body');
        const filters = document.getElementById('profit-loss-filters');

        body.innerHTML = '<div class="text-center text-muted py-5">' +
            '<span class="spinner-border spinner-border-sm me-2"></span>' + @json(__('Loading…')) + '</div>';

        (bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl)).show();

        // Same period as the grid, so the lines add up to the row's figures.
        const params = new URLSearchParams();
        new FormData(filters).forEach(function (value, key) {
            if (value) params.append(key, value);
        });

        const url = @json(route('profit-loss.breakdown', ['product' => '__ID__'])).replace('__ID__', productId);

        fetch(url + (params.toString() ? '?' + params.toString() : ''), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then(function (response) {
                if (!response.ok) throw new Error('request failed');
                return response.json();
            })
            .then(function (payload) {
                body.innerHTML = payload.html;
            })
            .catch(function () {
                body.innerHTML = '<div class="text-center text-danger py-5">' +
                    @json(__('Could not load this breakdown. Please try again.')) + '</div>';
            });
    }
</script>
@endpush
