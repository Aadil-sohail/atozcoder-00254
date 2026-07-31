@extends('layouts.header')

@section('title', 'Inventory')

@section('header')
    <h2 class="text-lg font-semibold leading-tight text-gray-800">
        {{ __('Inventory') }}
    </h2>
@endsection

@section('content')

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
            <div>
                <p class="mb-0 text-muted small">{{ __('Stock by category — open a category to see its products.') }}</p>
            </div>
            @can('create inventories')
            <button type="button" onclick="openStockModal()" class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                {{ __('Add Stock') }}
            </button>
            @endcan
        </div>

        <div class="table-responsive">
            <table id="inventory-categories-table" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Category') }}</th>
                        <th>{{ __('Products') }}</th>
                        <th>{{ __('Available Stock') }}</th>
                        <th class="no-sort">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    @include('inventory.partials.create-modal')

    @push('scripts')
    <script>
        // Rows are fetched a page at a time — see App\Support\ServerTable.
        // Product count and stock are SQL sub-queries, so they sort on the real
        // totals, but they are not text and so are left out of the search.
        $(function () {
            serverTable('#inventory-categories-table', {
                url: @json(route('inventories.data')),
                columns: [
                    { data: 'name' },
                    { data: 'products_count', searchable: false },
                    { data: 'available_stock', searchable: false },
                    { data: 'actions', orderable: false, searchable: false },
                ],
                order: [[0, 'asc']],
            });
        });

        function openStockModal() {
            new bootstrap.Modal(document.getElementById('create-inventory-modal')).show();
        }

        function closeStockModal() {
            const el = document.getElementById('create-inventory-modal');
            (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).hide();
        }
    </script>
    @endpush

@endsection
