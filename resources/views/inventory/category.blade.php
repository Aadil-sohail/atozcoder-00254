@extends('layouts.header')

@section('title', 'Inventory — ' . $category->name)

@section('header')
    <h2 class="text-lg font-semibold leading-tight text-gray-800">
        {{ __('Inventory') }}: {{ $category->name }}
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
                <h5 class="mb-1 fw-semibold">{{ $category->name }}</h5>
                <span class="text-muted small">{{ $productCount }} {{ Str::plural('product', $productCount) }} in this category</span>
            </div>
            <a href="{{ route('inventories.index') }}" class="btn btn-dark btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i>{{ __('Back') }}
            </a>
        </div>

        <div class="table-responsive">
            <table id="category-products-table" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Product') }}</th>
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
        serverTable('#category-products-table', {
            url: @json(route('inventories.category.data', $category)),
            columns: [
                { data: 'name' },
                { data: 'available_stock', searchable: false },
                { data: 'actions', orderable: false, searchable: false },
            ],
            order: [[0, 'asc']],
        });
    });
</script>
@endpush
