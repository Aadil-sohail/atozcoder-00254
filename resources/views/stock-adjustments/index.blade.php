@extends('layouts.header')

@section('title', 'Stock Adjustments')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('Stock Adjustments') }}</h2>
@endsection

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
            <div>
                <p class="mb-0 text-muted small">{{ __('Stock corrected by hand. Each adjustment is pushed to every eBay listing of the product.') }}</p>
                <span class="text-muted" style="font-size:12px;">{{ $adjustmentCount }} {{ Str::plural('adjustment', $adjustmentCount) }} total</span>
            </div>
            @can('create stock adjustments')
                <button type="button" class="btn btn-dark btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#adjustment-modal">
                    <i class="fa-solid fa-plus"></i>
                    {{ __('New Adjustment') }}
                </button>
            @endcan
        </div>

        <div class="table-responsive">
            <table id="adjustments-table" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Store') }}</th>
                        <th>{{ __('Product') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Change') }}</th>
                        <th>{{ __('Stock') }}</th>
                        <th>{{ __('eBay') }}</th>
                        <th>{{ __('Reason') }}</th>
                        <th>{{ __('By') }}</th>
                        <th class="no-sort">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    @can('create stock adjustments')
        @include('stock-adjustments.partials.create-modal')
    @endcan

    @can('edit stock adjustments')
        @include('stock-adjustments.partials.edit-modal')
    @endcan

@endsection

@push('scripts')
<script>
    // Rows are fetched a page at a time — see App\Support\ServerTable.
    $(function () {
        serverTable('#adjustments-table', {
            url: @json(route('stock-adjustments.data')),
            columns: [
                { data: 'created_at', searchable: false },
                { data: 'store_name' },
                { data: 'product_name' },
                { data: 'type', searchable: false },
                { data: 'change', searchable: false },
                { data: 'stock', searchable: false },
                { data: 'pushed', searchable: false },
                { data: 'reason' },
                { data: 'inserted_by' },
                { data: 'actions', orderable: false, searchable: false },
            ],
            // Newest first.
            order: [[0, 'desc']],
        });
    });
</script>
@endpush
