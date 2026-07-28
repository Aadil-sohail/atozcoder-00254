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
                <span class="text-muted" style="font-size:12px;">{{ $sales->count() }} {{ Str::plural('sale', $sales->count()) }} total</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                @can('sync ebay products')
                <form method="POST" action="{{ route('ebay.sync-orders') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-dark btn-sm d-flex align-items-center gap-2">
                        <i class="fa-brands fa-ebay"></i>
                        {{ __('Sync eBay Orders') }}
                    </button>
                </form>
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
                <tbody>
                    @forelse ($sales as $sale)
                        <tr>
                            <td>
                                <a href="{{ route('sales.show', $sale) }}" class="fw-medium text-decoration-none">
                                    {{ $sale->invoice_no }}
                                </a>
                            </td>
                            <td class="text-secondary">{{ $sale->customer->name }}</td>
                            <td class="text-secondary" data-order="{{ \Carbon\Carbon::parse($sale->sale_date)->timestamp }}">{{ \Carbon\Carbon::parse($sale->sale_date)->format('M d, Y') }}</td>
                            <td class="text-secondary">{{ number_format($sale->discount, 2) }}</td>
                            <td class="fw-medium">{{ number_format($sale->total_amount, 2) }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @can('delete sales')
                                    <form method="POST" action="{{ route('sales.destroy', $sale) }}" class="d-inline"
                                        onsubmit="return confirm('{{ __('Delete this sale? This cannot be undone.') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fa-solid fa-receipt fs-2 text-secondary opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-2">{{ __('No sales recorded yet.') }}</p>
                                @can('create sales')
                                    <a href="{{ route('sales.create') }}" class="btn btn-sm btn-dark">
                                        {{ __('Create first sale') }}
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    $(function () {
        $('#sales-table').DataTable({
            columnDefs: [
                { orderable: false, targets: 'no-sort' },
            ],
            order: [],
        });
    });
</script>
@endpush
