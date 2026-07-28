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
                <span class="text-muted" style="font-size:12px;">{{ $returns->count() }} {{ Str::plural('return', $returns->count()) }} total</span>
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
                <tbody>
                    @foreach ($returns as $return)
                        <tr>
                            <td>
                                <a href="{{ route('returns.show', $return) }}" class="fw-medium text-decoration-none">
                                    {{ $return->sale->invoice_no }}
                                </a>
                            </td>
                            <td class="text-secondary">{{ $return->sale->customer->name }}</td>
                            <td class="text-secondary" data-order="{{ \Carbon\Carbon::parse($return->return_date)->timestamp }}">{{ \Carbon\Carbon::parse($return->return_date)->format('M d, Y') }}</td>
                            <td class="text-secondary">{{ $return->items->count() }} {{ Str::plural('item', $return->items->count()) }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('returns.show', $return) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @can('delete returns')
                                    <form method="POST" action="{{ route('returns.destroy', $return) }}" class="d-inline"
                                        onsubmit="return confirmDelete(event, {{ json_encode(__('Delete this return? Any restocked quantity will be removed again.')) }});">
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    $(function () {
        $('#returns-table').DataTable({
            columnDefs: [
                { orderable: false, targets: 'no-sort' },
            ],
            order: [],
        });
    });
</script>
@endpush
