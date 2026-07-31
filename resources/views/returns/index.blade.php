@extends('layouts.header')

@section('title', 'Returns')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('Returns') }}</h2>
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

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
            <div>
                <p class="mb-0 text-muted small">{{ __('Sale returns recorded across all invoices.') }}</p>
                <span class="text-muted" style="font-size:12px;">{{ $returnCount }} {{ Str::plural('return', $returnCount) }} total</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                @can('sync ebay products')
                <form method="POST" action="{{ route('ebay.sync-returns') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-dark btn-sm d-flex align-items-center gap-2">
                        <i class="fa-brands fa-ebay"></i>
                        {{ __('Sync eBay Returns') }}
                    </button>
                </form>
                @endcan
                @can('create returns')
                <a href="{{ route('returns.create') }}" class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    {{ __('New Return') }}
                </a>
                @endcan
            </div>
        </div>

        <div class="table-responsive">
            <table id="returns-table" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Invoice No') }}</th>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Return Date') }}</th>
                        <th>{{ __('Items') }}</th>
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
        serverTable('#returns-table', {
            url: @json(route('returns.data')),
            columns: [
                { data: 'invoice_no' },
                { data: 'customer_name' },
                { data: 'return_date' },
                { data: 'items_count', searchable: false },
                { data: 'actions', orderable: false, searchable: false },
            ],
            // Newest returns first, as the page has always shown them.
            order: [[2, 'desc']],
        });
    });
</script>
@endpush
