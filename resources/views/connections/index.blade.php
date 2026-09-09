@extends('layouts.header')

@section('title', 'Connections')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('Connections') }}</h2>
@endsection

@section('content')

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 p-2">
        <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
            <div>
                <p class="mb-0 text-muted small">
                    {{ __('The same part listed on more than one store, joined into a single product with one price and one pile of stock.') }}
                </p>
                <span class="text-muted" style="font-size:12px;">
                    {{ $connectionCount }} {{ Str::plural('connection', $connectionCount) }} total
                </span>
            </div>
            @can('create connections')
                <a href="{{ route('connections.create') }}" class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                    <i class="fa-solid fa-link"></i>
                    {{ __('New Connection') }}
                </a>
            @endcan
        </div>

        @if ($ebayAccounts->isEmpty())
            <div class="alert alert-warning m-3 mb-0 small">
                {{ __('No eBay store is connected yet, so there is nothing to join across. Connect a store first.') }}
            </div>
        @endif

        <div class="table-responsive">
            <table id="connections-table" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Connection') }}</th>
                        <th>{{ __('Products') }}</th>
                        <th class="no-sort">{{ __('Listed On') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Stock') }}</th>
                        <th class="no-sort">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Rows are fetched a page at a time — see App\Support\ServerTable.
    $(function () {
        serverTable('#connections-table', {
            url: @json(route('connections.data')),
            columns: [
                { data: 'name' },
                { data: 'items_count' },
                { data: 'stores', orderable: false, searchable: false },
                { data: 'master_selling_price' },
                { data: 'available_stock' },
                { data: 'actions', orderable: false, searchable: false },
            ],
            order: [[0, 'asc']],
        });
    });
</script>
@endpush
