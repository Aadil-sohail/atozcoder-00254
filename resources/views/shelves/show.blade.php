@extends('layouts.header')

@section('title', 'Shelf ' . $shelf->name)

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('Shelf') }}: {{ $shelf->rack->name }} › {{ $shelf->name }}</h2>
@endsection

@section('content')

{{-- Shelf Info --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
        <span class="fw-semibold">{{ __('Shelf Info') }}</span>
        <a href="{{ route('shelves.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i>{{ __('Back') }}
        </a>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <p class="text-muted small mb-1">{{ __('Rack') }}</p>
                <p class="fw-semibold mb-0">{{ $shelf->rack->name }}</p>
            </div>
            <div class="col-md-3">
                <p class="text-muted small mb-1">{{ __('Shelf') }}</p>
                <p class="fw-semibold mb-0">
                    {{ $shelf->name }}
                    @if ($shelf->status !== '1')
                        <span class="badge bg-secondary ms-1">{{ __('Deleted') }}</span>
                    @endif
                </p>
            </div>
            <div class="col-md-3">
                <p class="text-muted small mb-1">{{ __('Products') }}</p>
                <p class="fw-semibold mb-0">{{ $products->count() }}</p>
            </div>
            <div class="col-md-3">
                <p class="text-muted small mb-1">{{ __('Units on this shelf') }}</p>
                <p class="fw-bold fs-5 text-success mb-0">
                    {{ rtrim(rtrim(number_format($products->sum(fn ($p) => max(0, $p->availableStock())), 2), '0'), '.') }}
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Products --}}
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <span class="fw-semibold">{{ __('Products on this shelf') }}</span>
    </div>
    <div class="table-responsive">
        <table id="shelf-products-table" class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="no-sort" style="width:56px;"></th>
                    <th>{{ __('Product') }}</th>
                    <th>{{ __('SKU') }}</th>
                    <th>{{ __('Category') }}</th>
                    <th class="no-sort">{{ __('Stores') }}</th>
                    <th>{{ __('In Stock') }}</th>
                    <th class="no-sort">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    @php($stock = $product->availableStock())
                    <tr>
                        <td>@include('products.partials.cells.image')</td>
                        <td>@include('products.partials.cells.name')</td>
                        <td class="text-secondary">{{ $product->sku ?: '—' }}</td>
                        <td class="text-secondary">{{ $product->category->name ?? '—' }}</td>
                        <td>@include('products.partials.cells.stores')</td>
                        <td data-order="{{ $stock }}" class="{{ $stock > 0 ? 'fw-medium' : 'text-danger fw-medium' }}">
                            {{ rtrim(rtrim(number_format($stock, 2), '0'), '.') }}
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('View product') }}">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                @can('edit products')
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="{{ __('Edit product or move it to another shelf') }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fa-solid fa-box-open fs-2 text-secondary opacity-50 d-block mb-2"></i>
                            <p class="text-muted mb-0">{{ __('No products on this shelf yet. Pick this shelf on a product\'s edit page to put it here.') }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
    <script>
        $(function () {
            @if ($products->isNotEmpty())
                $('#shelf-products-table').DataTable({
                    columnDefs: [{ orderable: false, targets: 'no-sort' }],
                    order: [[1, 'asc']],
                });
            @endif
        });
    </script>
@endpush

@endsection
