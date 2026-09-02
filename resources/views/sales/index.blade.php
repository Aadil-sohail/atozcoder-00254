@extends('layouts.header')

@section('title', 'Sales')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('Sales') }}</h2>
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
                <p class="mb-0 text-muted small">{{ __('All recorded sales and invoices.') }}</p>
                <span class="text-muted" style="font-size:12px;">{{ $saleCount }} {{ Str::plural('sale', $saleCount) }} total</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                @can('sync ebay products')
                    @if ($ebayAccounts->isNotEmpty())
                    <button type="button" class="btn btn-outline-dark btn-sm d-flex align-items-center gap-2"
                        data-bs-toggle="modal" data-bs-target="#ebayOrderSyncModal">
                        <i class="fa-brands fa-ebay"></i>
                        {{ __('Sync eBay Orders') }}
                    </button>
                    @endif
                @endcan
                @can('create sales')
                <a href="{{ route('sales.create') }}" class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    {{ __('New Sale') }}
                </a>
                @endcan
            </div>
        </div>

        <div class="table-responsive">
            <table id="sales-table" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Invoice No') }}</th>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Sale Date') }}</th>
                        <th>{{ __('Discount') }}</th>
                        <th>{{ __('Total') }}</th>
                        <th class="no-sort">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    @can('sync ebay products')
        @if ($ebayAccounts->isNotEmpty())
        <div class="modal fade" id="ebayOrderSyncModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('ebay.sync-orders') }}" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa-brands fa-ebay me-2"></i>{{ __('Sync Orders from eBay') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted mb-3">
                            {{ __('Recent orders from the selected store are pulled in and recorded as sales. Orders already here are left alone, and anything sold that is not a product yet is added from its eBay listing.') }}
                        </p>
                        <div class="mb-1">
                            <label class="form-label small text-muted mb-1">{{ __('eBay store') }}</label>
                            <select name="ebay_account_id" class="form-select form-select-sm" required>
                                @foreach ($ebayAccounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->store_name }}
                                        ({{ config("ebay.marketplaces.{$account->marketplace_id}.label", $account->marketplace_id) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-dark btn-sm">
                            <i class="fa-solid fa-download me-1"></i>{{ __('Sync orders') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    @endcan

@endsection

@push('scripts')
<script>
    // Rows are fetched a page at a time — see App\Support\ServerTable.
    $(function () {
        serverTable('#sales-table', {
            url: @json(route('sales.data')),
            columns: [
                { data: 'invoice_no' },
                { data: 'customer_name' },
                { data: 'sale_date' },
                { data: 'discount' },
                { data: 'total_amount' },
                { data: 'actions', orderable: false, searchable: false },
            ],
            // Newest sales first, as the page has always shown them.
            order: [[2, 'desc']],
        });
    });
</script>
@endpush
