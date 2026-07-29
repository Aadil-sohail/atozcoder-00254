@extends('layouts.header')

@section('title', 'Dashboard')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('Dashboard') }}</h2>
@endsection

@push('styles')
<style>
    /* ── Dashboard tokens (validated palette; light surface #fff) ───────── */
    .dash {
        --surface: #ffffff;
        --ink: #0b0b0b;
        --ink-2: #52514e;
        --muted: #898781;
        --grid: #e1e0d9;
        --ring: rgba(11, 11, 11, .09);
        --s1: #2a78d6;   /* series 1 · blue   */
        --s2: #eb6834;   /* series 2 · orange */
        --s3: #1baf7a;   /* series 3 · aqua   */
        --s4: #eda100;   /* series 4 · yellow */
        --s5: #e87ba4;   /* series 5 · magenta*/
        --s6: #008300;   /* series 6 · green  */
        --good: #006300;
        --warning: #fab219;
        --serious: #ec835a;
        --critical: #d03b3b;
    }

    .dash-card {
        background: var(--surface);
        border: 1px solid var(--ring);
        border-radius: 12px;
        height: 100%;
    }
    .dash-card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 18px 20px 0;
    }
    .dash-card-title { font-size: 14px; font-weight: 600; color: var(--ink); margin: 0; }
    .dash-card-sub { font-size: 12px; color: var(--muted); margin: 2px 0 0; }
    .dash-card-body { padding: 16px 20px 20px; }

    /* ── KPI stat tiles ─────────────────────────────────────────────────── */
    .dash-tile { padding: 18px 20px; }
    .dash-tile-top { display: flex; align-items: center; justify-content: space-between; }
    .dash-icon {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 15px; flex-shrink: 0;
    }
    .dash-label {
        font-size: 12px; font-weight: 500; color: var(--muted);
        margin: 14px 0 2px; letter-spacing: .01em;
    }
    .dash-value {
        font-size: 30px; line-height: 1.15; font-weight: 600; color: var(--ink); margin: 0;
    }
    .dash-meta { font-size: 12px; color: var(--ink-2); margin: 8px 0 0; }
    .dash-delta {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 12px; font-weight: 600; margin: 8px 0 0;
    }
    .dash-delta.up { color: var(--good); }
    .dash-delta.down { color: var(--critical); }
    .dash-delta.flat { color: var(--muted); }
    .dash-delta span { font-weight: 500; color: var(--muted); }

    /* ── Horizontal HTML bars (top products) ────────────────────────────── */
    .dash-bar-row { padding: 10px 0; }
    .dash-bar-row + .dash-bar-row { border-top: 1px solid var(--grid); }
    .dash-bar-head {
        display: flex; align-items: baseline; justify-content: space-between; gap: 12px;
        font-size: 13px; margin-bottom: 7px;
    }
    .dash-bar-name { color: var(--ink); font-weight: 500; min-width: 0; }
    .dash-bar-val { color: var(--ink-2); font-weight: 600; white-space: nowrap; }
    .dash-bar-track { height: 8px; background: #f1f0ec; border-radius: 4px; overflow: hidden; }
    .dash-bar-fill {
        height: 100%; background: var(--s1);
        border-radius: 0 4px 4px 0;   /* 4px rounded data-end, square at baseline */
        min-width: 3px;
    }

    /* ── Donut legend (doubles as the donut's table view) ───────────────── */
    .dash-legend { display: flex; flex-direction: column; gap: 2px; margin: 14px 0 0; }
    .dash-legend-row {
        display: flex; align-items: center; gap: 9px;
        font-size: 13px; padding: 5px 0;
    }
    .dash-legend-row + .dash-legend-row { border-top: 1px solid var(--grid); }
    .dash-swatch { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }
    .dash-legend-name {
        color: var(--ink-2); flex: 1; min-width: 0;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .dash-legend-val { color: var(--ink); font-weight: 600; font-variant-numeric: tabular-nums; }
    .dash-legend-pct { color: var(--muted); font-size: 12px; width: 42px; text-align: right; font-variant-numeric: tabular-nums; }

    /* ── Alert / status rows ────────────────────────────────────────────── */
    .dash-row {
        display: flex; align-items: center; gap: 12px;
        padding: 11px 0; font-size: 13px;
    }
    .dash-row + .dash-row { border-top: 1px solid var(--grid); }
    .dash-row-main { flex: 1; min-width: 0; }
    .dash-row-name {
        color: var(--ink); font-weight: 500; display: block;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .dash-row-sub { color: var(--muted); font-size: 12px; }
    .dash-pill {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 11px; font-weight: 600; padding: 3px 9px;
        border-radius: 999px; white-space: nowrap;
    }
    .dash-pill.critical { color: #8f2020; background: rgba(208, 59, 59, .12); }
    .dash-pill.warning { color: #7a5300; background: rgba(250, 178, 25, .18); }
    .dash-pill.good { color: var(--good); background: rgba(12, 163, 12, .12); }
    .dash-pill.neutral { color: var(--ink-2); background: #f1f0ec; }

    /* ── Chart chrome ───────────────────────────────────────────────────── */
    .dash-plot { position: relative; height: 280px; }
    .dash-plot-sm { position: relative; height: 210px; }
    .dash-toggle {
        border: 1px solid var(--ring); background: var(--surface); color: var(--ink-2);
        font-size: 12px; font-weight: 500; padding: 4px 10px; border-radius: 7px;
        white-space: nowrap;
    }
    .dash-toggle:hover { background: #f7f6f3; color: var(--ink); }
    .dash-table { font-size: 13px; margin: 0; }
    .dash-table th {
        font-size: 11px; font-weight: 600; color: var(--muted);
        text-transform: uppercase; letter-spacing: .04em;
    }
    .dash-table td { color: var(--ink-2); font-variant-numeric: tabular-nums; }

    .dash-empty { padding: 34px 12px; text-align: center; color: var(--muted); font-size: 13px; }
    .dash-empty i { font-size: 26px; display: block; margin-bottom: 10px; opacity: .5; }

    .dash-link { color: var(--s1); text-decoration: none; font-weight: 500; }
    .dash-link:hover { text-decoration: underline; }
</style>
@endpush

@section('content')
@php
    /* Money for a stat tile: exact where it matters, compact once it stops fitting. */
    $money = function ($n) {
        $n = (float) $n;
        if (abs($n) >= 1000000) return number_format($n / 1000000, 2) . 'M';
        if (abs($n) >= 100000)  return number_format($n / 1000, 1) . 'K';
        return number_format($n, 2);
    };
    $qty = fn ($n) => rtrim(rtrim(number_format((float) $n, 2), '0'), '.');

    $trendTotal = $trend ? array_sum(array_column($trend, 'revenue')) : 0;
    $catTotal   = $categories ? array_sum(array_column($categories, 'revenue')) : 0;
    $topMax     = $topProducts && $topProducts->isNotEmpty() ? (float) $topProducts->max('units') : 0;

    /* A single slice is a full ring — no information. The legend states it instead. */
    $showCatChart = $catTotal > 0 && count($categories) > 1;
@endphp

<div class="dash">

    {{-- ── Greeting + quick actions ──────────────────────────────────── --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="fs-4 fw-semibold mb-1" style="color:var(--ink);">
                {{ __('Welcome back') }}, {{ auth()->user()->name }}
            </h1>
            <p class="mb-0" style="font-size:13px; color:var(--muted);">
                {{ $today->format('l, d F Y') }} &middot; {{ __('Overview for') }} {{ $monthLabel }}
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @can('create sales')
                <a href="{{ route('sales.create') }}" class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                    <i class="fa-solid fa-plus"></i>{{ __('New Sale') }}
                </a>
            @endcan
            @can('create products')
                <a href="{{ route('products.create') }}" class="btn btn-outline-dark btn-sm d-flex align-items-center gap-2">
                    <i class="fa-solid fa-box"></i>{{ __('Add Product') }}
                </a>
            @endcan
            @can('create returns')
                <a href="{{ route('returns.create') }}" class="btn btn-outline-dark btn-sm d-flex align-items-center gap-2">
                    <i class="fa-solid fa-rotate-left"></i>{{ __('New Return') }}
                </a>
            @endcan
        </div>
    </div>

    {{-- ── KPI row ───────────────────────────────────────────────────── --}}
    <div class="row g-3 mb-3">
        @if ($sales)
            <div class="col-6 col-xl-3">
                <div class="dash-card dash-tile">
                    <div class="dash-tile-top">
                        <div class="dash-icon" style="background:rgba(42,120,214,.12); color:var(--s1);">
                            <i class="fa-solid fa-sack-dollar"></i>
                        </div>
                    </div>
                    <p class="dash-label">{{ __('Revenue this month') }}</p>
                    <p class="dash-value">{{ $money($sales->month_revenue) }}</p>
                    @if ($sales->revenue_delta !== null)
                        <p class="dash-delta {{ $sales->revenue_delta > 0 ? 'up' : ($sales->revenue_delta < 0 ? 'down' : 'flat') }}">
                            <i class="fa-solid fa-arrow-trend-{{ $sales->revenue_delta >= 0 ? 'up' : 'down' }}"></i>
                            {{ $sales->revenue_delta > 0 ? '+' : '' }}{{ $sales->revenue_delta }}%
                            <span>{{ __('vs last month') }}</span>
                        </p>
                    @else
                        <p class="dash-meta">{{ __('Today') }}: {{ $money($sales->today_revenue) }}</p>
                    @endif
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="dash-card dash-tile">
                    <div class="dash-tile-top">
                        <div class="dash-icon" style="background:rgba(27,175,122,.14); color:#12805a;">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                    </div>
                    <p class="dash-label">{{ __('Orders this month') }}</p>
                    <p class="dash-value">{{ number_format($sales->month_orders) }}</p>
                    @if ($sales->orders_delta !== null)
                        <p class="dash-delta {{ $sales->orders_delta > 0 ? 'up' : ($sales->orders_delta < 0 ? 'down' : 'flat') }}">
                            <i class="fa-solid fa-arrow-trend-{{ $sales->orders_delta >= 0 ? 'up' : 'down' }}"></i>
                            {{ $sales->orders_delta > 0 ? '+' : '' }}{{ $sales->orders_delta }}%
                            <span>{{ __('vs last month') }}</span>
                        </p>
                    @else
                        <p class="dash-meta">{{ $sales->today_orders }} {{ __('today') }}</p>
                    @endif
                </div>
            </div>
        @endif

        @if ($stock)
            <div class="col-6 col-xl-3">
                <div class="dash-card dash-tile">
                    <div class="dash-tile-top">
                        <div class="dash-icon" style="background:rgba(74,58,167,.12); color:#4a3aa7;">
                            <i class="fa-solid fa-warehouse"></i>
                        </div>
                    </div>
                    <p class="dash-label">{{ __('Stock value at cost') }}</p>
                    <p class="dash-value">{{ $money($stock->cost_value) }}</p>
                    <p class="dash-meta">
                        {{ $qty($stock->units) }} {{ __('units') }} &middot; {{ number_format($stock->products) }} {{ __('products') }}
                    </p>
                </div>
            </div>
        @endif

        @if ($returns)
            <div class="col-6 col-xl-3">
                <div class="dash-card dash-tile">
                    <div class="dash-tile-top">
                        <div class="dash-icon" style="background:rgba(235,104,52,.12); color:var(--s2);">
                            <i class="fa-solid fa-rotate-left"></i>
                        </div>
                    </div>
                    <p class="dash-label">{{ __('Returns this month') }}</p>
                    <p class="dash-value">{{ number_format($returns->month) }}</p>
                    <p class="dash-meta">{{ number_format($returns->total) }} {{ __('recorded in total') }}</p>
                </div>
            </div>
        @elseif ($customers)
            <div class="col-6 col-xl-3">
                <div class="dash-card dash-tile">
                    <div class="dash-tile-top">
                        <div class="dash-icon" style="background:rgba(232,123,164,.14); color:#c94c7c;">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                    </div>
                    <p class="dash-label">{{ __('Customers') }}</p>
                    <p class="dash-value">{{ number_format($customers->total) }}</p>
                    <p class="dash-meta">+{{ number_format($customers->new_this_month) }} {{ __('this month') }}</p>
                </div>
            </div>
        @endif
    </div>

    {{-- ── Revenue trend + category mix ──────────────────────────────── --}}
    @if ($trend || $categories)
        <div class="row g-3 mb-3">
            @if ($trend)
                <div class="col-12 col-xl-8">
                    <div class="dash-card" id="revenue-trend">
                        <div class="dash-card-head">
                            <div>
                                <p class="dash-card-title">{{ __('Revenue trend') }}</p>
                                <p class="dash-card-sub">{{ __('Last 12 months') }} &middot; {{ $money($trendTotal) }} {{ __('total') }}</p>
                            </div>
                            @if ($trendTotal > 0)
                                <button type="button" class="dash-toggle" data-view-toggle="revenue-trend">
                                    <i class="fa-solid fa-table-list me-1"></i>{{ __('Table') }}
                                </button>
                            @endif
                        </div>
                        <div class="dash-card-body">
                            @if ($trendTotal > 0)
                                <div data-view="chart">
                                    <div class="dash-plot"><canvas id="trendChart"></canvas></div>
                                </div>
                                <div data-view="table" hidden>
                                    <div class="table-responsive" style="max-height:280px;">
                                        <table class="table table-sm dash-table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Month') }}</th>
                                                    <th class="text-end">{{ __('Revenue') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($trend as $point)
                                                    <tr>
                                                        <td>{{ $point['label'] }}</td>
                                                        <td class="text-end">{{ number_format($point['revenue'], 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="dash-empty">
                                    <i class="fa-solid fa-chart-line"></i>
                                    {{ __('No sales recorded in the last 12 months yet.') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if ($categories !== null)
                <div class="col-12 col-xl-4">
                    <div class="dash-card">
                        <div class="dash-card-head">
                            <div>
                                <p class="dash-card-title">{{ __('Revenue by category') }}</p>
                                <p class="dash-card-sub">{{ __('Last 12 months') }}</p>
                            </div>
                        </div>
                        <div class="dash-card-body">
                            @if ($catTotal > 0)
                                @if ($showCatChart)
                                    <div class="dash-plot-sm"><canvas id="categoryChart"></canvas></div>
                                @endif
                                {{-- Visible values: the relief for sub-3:1 slot contrast, and this donut's table view. --}}
                                <div class="dash-legend">
                                    @foreach ($categories as $i => $slice)
                                        <div class="dash-legend-row">
                                            <span class="dash-swatch" style="background:var(--s{{ $i + 1 }});"></span>
                                            <span class="dash-legend-name">{{ $slice['name'] }}</span>
                                            <span class="dash-legend-val">{{ number_format($slice['revenue'], 2) }}</span>
                                            <span class="dash-legend-pct">{{ round($slice['revenue'] / $catTotal * 100) }}%</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="dash-empty">
                                    <i class="fa-solid fa-chart-pie"></i>
                                    {{ __('No category revenue to show yet.') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- ── Top products + recent sales ───────────────────────────────── --}}
    <div class="row g-3 mb-3">
        @if ($topProducts !== null)
            <div class="col-12 col-xl-5">
                <div class="dash-card">
                    <div class="dash-card-head">
                        <div>
                            <p class="dash-card-title">{{ __('Top selling products') }}</p>
                            <p class="dash-card-sub">{{ __('By units sold, last 12 months') }}</p>
                        </div>
                        @can('view products')
                            <a href="{{ route('products.index') }}" class="dash-toggle text-decoration-none">{{ __('All') }}</a>
                        @endcan
                    </div>
                    <div class="dash-card-body">
                        @forelse ($topProducts as $product)
                            <div class="dash-bar-row">
                                <div class="dash-bar-head">
                                    <span class="dash-bar-name text-truncate">{{ $product->name }}</span>
                                    <span class="dash-bar-val">{{ $qty($product->units) }} {{ __('units') }}</span>
                                </div>
                                <div class="dash-bar-track">
                                    <div class="dash-bar-fill" style="width:{{ $topMax > 0 ? max(2, round($product->units / $topMax * 100, 1)) : 0 }}%;"></div>
                                </div>
                            </div>
                        @empty
                            <div class="dash-empty">
                                <i class="fa-solid fa-ranking-star"></i>
                                {{ __('No products sold in this period yet.') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif

        @if ($recentSales !== null)
            <div class="col-12 col-xl-7">
                <div class="dash-card">
                    <div class="dash-card-head">
                        <div>
                            <p class="dash-card-title">{{ __('Recent sales') }}</p>
                            <p class="dash-card-sub">
                                {{ __('Latest invoices') }}@if ($customers) &middot; {{ number_format($customers->total) }} {{ __('customers') }}@endif
                            </p>
                        </div>
                        <a href="{{ route('sales.index') }}" class="dash-toggle text-decoration-none">{{ __('All') }}</a>
                    </div>
                    <div class="dash-card-body pt-2">
                        @forelse ($recentSales as $sale)
                            <div class="dash-row">
                                <div class="dash-row-main">
                                    <a href="{{ route('sales.show', $sale) }}" class="dash-row-name dash-link">{{ $sale->invoice_no }}</a>
                                    <span class="dash-row-sub">
                                        {{ $sale->customer?->name ?? __('Walk-in') }} &middot;
                                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('M d, Y') }}
                                    </span>
                                </div>
                                @if ($sale->ebay_order_id)
                                    <span class="dash-pill neutral"><i class="fa-brands fa-ebay"></i></span>
                                @endif
                                <span class="fw-semibold" style="color:var(--ink); font-variant-numeric:tabular-nums;">
                                    {{ number_format($sale->total_amount, 2) }}
                                </span>
                            </div>
                        @empty
                            <div class="dash-empty">
                                <i class="fa-solid fa-receipt"></i>
                                {{ __('No sales recorded yet.') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- ── Stock alerts + eBay sync ──────────────────────────────────── --}}
    <div class="row g-3">
        @if ($lowStock !== null)
            <div class="col-12 col-xl-8">
                <div class="dash-card">
                    <div class="dash-card-head">
                        <div>
                            <p class="dash-card-title">{{ __('Stock alerts') }}</p>
                            <p class="dash-card-sub">
                                {{ number_format($stock->out_of_stock) }} {{ __('out of stock') }} &middot;
                                {{ number_format($stock->low_stock) }} {{ __('running low') }}
                            </p>
                        </div>
                        <a href="{{ route('out-of-stock.index') }}" class="dash-toggle text-decoration-none">{{ __('View all') }}</a>
                    </div>
                    <div class="dash-card-body pt-2">
                        @forelse ($lowStock as $product)
                            <div class="dash-row">
                                <div class="dash-row-main">
                                    <a href="{{ route('products.show', $product->id) }}" class="dash-row-name dash-link">{{ $product->name }}</a>
                                    <span class="dash-row-sub">{{ $product->sku ?: __('No SKU') }}</span>
                                </div>
                                @if ($product->stock <= 0)
                                    <span class="dash-pill critical">
                                        <i class="fa-solid fa-circle-exclamation"></i>{{ __('Out of stock') }}
                                    </span>
                                @else
                                    <span class="dash-pill warning">
                                        <i class="fa-solid fa-triangle-exclamation"></i>{{ $qty($product->stock) }} {{ __('left') }}
                                    </span>
                                @endif
                            </div>
                        @empty
                            <div class="dash-empty">
                                <i class="fa-solid fa-circle-check"></i>
                                {{ __('Every product is comfortably in stock.') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif

        @if ($ebay)
            <div class="col-12 col-xl-4">
                <div class="dash-card">
                    <div class="dash-card-head">
                        <div>
                            <p class="dash-card-title">{{ __('eBay sync') }}</p>
                            <p class="dash-card-sub">
                                {{ number_format($ebay->stores) }} {{ Str::plural('store', $ebay->stores) }} &middot;
                                {{ number_format($ebay->total) }} {{ Str::plural('listing', $ebay->total) }}
                            </p>
                        </div>
                        <a href="{{ route('ebay.index') }}" class="dash-toggle text-decoration-none">{{ __('Manage') }}</a>
                    </div>
                    <div class="dash-card-body pt-2">
                        @if ($ebay->total > 0)
                            <div class="dash-row">
                                <span class="dash-row-main dash-row-name">{{ __('Synced') }}</span>
                                <span class="dash-pill good"><i class="fa-solid fa-circle-check"></i>{{ number_format($ebay->synced) }}</span>
                            </div>
                            <div class="dash-row">
                                <span class="dash-row-main dash-row-name">{{ __('Pending') }}</span>
                                <span class="dash-pill neutral"><i class="fa-solid fa-clock"></i>{{ number_format($ebay->pending) }}</span>
                            </div>
                            <div class="dash-row">
                                <span class="dash-row-main dash-row-name">{{ __('Failed') }}</span>
                                <span class="dash-pill {{ $ebay->failed > 0 ? 'critical' : 'neutral' }}">
                                    <i class="fa-solid fa-circle-exclamation"></i>{{ number_format($ebay->failed) }}
                                </span>
                            </div>
                            @if ($sales && $sales->month_ebay_orders > 0)
                                <div class="dash-row">
                                    <span class="dash-row-main dash-row-name">{{ __('eBay orders this month') }}</span>
                                    <span class="fw-semibold" style="color:var(--ink);">{{ number_format($sales->month_ebay_orders) }}</span>
                                </div>
                            @endif
                        @else
                            <div class="dash-empty">
                                <i class="fa-brands fa-ebay"></i>
                                {{ __('No listings pushed to eBay yet.') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
@if (($trendTotal ?? 0) > 0 || ($showCatChart ?? false))
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(function () {
    const INK     = '#0b0b0b',
          MUTED   = '#898781',
          GRID    = '#e1e0d9',
          BASE    = '#c3c2b7',
          SURFACE = '#ffffff',
          SERIES  = ['#2a78d6', '#eb6834', '#1baf7a', '#eda100', '#e87ba4', '#008300'];

    const FONT = 'system-ui, -apple-system, "Segoe UI", sans-serif';
    const nf = new Intl.NumberFormat(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    // Axis ticks stay short; the exact figure lives in the tooltip and table view.
    const short = (v) => Math.abs(v) >= 1e6 ? (v / 1e6).toFixed(1) + 'M'
                       : Math.abs(v) >= 1e3 ? (v / 1e3).toFixed(1) + 'K'
                       : v.toLocaleString();

    Chart.defaults.font.family = FONT;
    Chart.defaults.font.size = 11;
    Chart.defaults.color = MUTED;

    const tooltip = {
        backgroundColor: INK,
        titleFont: { size: 12, weight: '600' },
        bodyFont: { size: 12 },
        padding: 10,
        cornerRadius: 8,
        displayColors: false,
    };

    // Crosshair: a hairline dropped at the hovered index.
    const crosshair = {
        id: 'crosshair',
        afterDatasetsDraw(chart) {
            const active = chart.tooltip?.getActiveElements?.() ?? [];
            if (!active.length) return;
            const { ctx, chartArea } = chart, x = active[0].element.x;
            ctx.save();
            ctx.strokeStyle = BASE;
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(x, chartArea.top);
            ctx.lineTo(x, chartArea.bottom);
            ctx.stroke();
            ctx.restore();
        },
    };

    // One direct label, on the endpoint only — the axis carries the rest.
    const endLabel = {
        id: 'endLabel',
        afterDatasetsDraw(chart) {
            const points = chart.getDatasetMeta(0).data;
            const last = points[points.length - 1];
            if (!last) return;
            const value = chart.data.datasets[0].data[points.length - 1];
            const ctx = chart.ctx;
            ctx.save();
            ctx.font = '600 12px ' + FONT;
            ctx.fillStyle = INK;
            ctx.textAlign = 'right';
            ctx.textBaseline = 'bottom';
            ctx.fillText(short(value), last.x, last.y - 11);
            ctx.restore();
        },
    };

    // ── Revenue trend: single series, so no legend — the title names it. ──
    const trendEl = document.getElementById('trendChart');
    if (trendEl) {
        const points = @json($trend ?? []);
        new Chart(trendEl, {
            type: 'line',
            data: {
                labels: points.map((p) => p.label),
                datasets: [{
                    label: @json(__('Revenue')),
                    data: points.map((p) => p.revenue),
                    borderColor: SERIES[0],
                    borderWidth: 2,
                    fill: true,
                    backgroundColor: 'rgba(42, 120, 214, 0.10)',   // ~10% wash
                    tension: 0.32,
                    pointRadius: (c) => c.dataIndex === c.dataset.data.length - 1 ? 4.5 : 0,
                    pointHoverRadius: 5,
                    pointBackgroundColor: SERIES[0],
                    pointBorderColor: SURFACE,
                    pointBorderWidth: 2,                            // 2px surface ring
                    pointHitRadius: 24,                             // generous hit target
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: { padding: { top: 22, right: 6 } },
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        ...tooltip,
                        callbacks: { label: (c) => nf.format(c.parsed.y) },
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { color: BASE },
                        ticks: { color: MUTED, maxRotation: 0, autoSkipPadding: 12 },
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: GRID, drawTicks: false },
                        border: { display: false },
                        ticks: { color: MUTED, padding: 8, maxTicksLimit: 5, callback: short },
                    },
                },
            },
            plugins: [crosshair, endLabel],
        });
    }

    // ── Category mix: ≤6 slots in fixed order, 2px surface gap between them. ──
    const catEl = document.getElementById('categoryChart');
    if (catEl) {
        const slices = @json($categories ?? []);
        const total = slices.reduce((sum, s) => sum + s.revenue, 0);
        new Chart(catEl, {
            type: 'doughnut',
            data: {
                labels: slices.map((s) => s.name),
                datasets: [{
                    data: slices.map((s) => s.revenue),
                    backgroundColor: slices.map((_, i) => SERIES[i]),
                    borderColor: SURFACE,
                    borderWidth: 2,
                    hoverOffset: 6,
                }],
            },
            options: {
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: { display: false },                     // the HTML legend below carries identity
                    tooltip: {
                        ...tooltip,
                        callbacks: {
                            label: (c) => nf.format(c.parsed) + '  (' + Math.round(c.parsed / total * 100) + '%)',
                        },
                    },
                },
            },
        });
    }

    // ── Chart ⇄ table view toggle ──────────────────────────────────────
    document.querySelectorAll('[data-view-toggle]').forEach((button) => {
        button.addEventListener('click', function () {
            const card = document.getElementById(this.dataset.viewToggle);
            const chart = card.querySelector('[data-view="chart"]');
            const table = card.querySelector('[data-view="table"]');
            const showTable = chart.hidden === false;

            chart.hidden = showTable;
            table.hidden = !showTable;
            this.innerHTML = showTable
                ? '<i class="fa-solid fa-chart-line me-1"></i>' + @json(__('Chart'))
                : '<i class="fa-solid fa-table-list me-1"></i>' + @json(__('Table'));
        });
    });
})();
</script>
@endif
@endpush
