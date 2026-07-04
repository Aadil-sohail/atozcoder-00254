@extends('layouts.header')

@section('title', 'Out of Stock')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('Out of Stock') }}</h2>
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
                <p class="mb-0 text-muted small">{{ __('Products with no stock left to sell.') }}</p>
                <span class="text-muted" style="font-size:12px;">{{ $products->total() }} {{ Str::plural('product', $products->total()) }} out of stock</span>
            </div>
            @can('create inventories')
            <a href="{{ route('inventories.index') }}" class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                {{ __('Add Stock') }}
            </a>
            @endcan
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Product') }}</th>
                        <th>{{ __('SKU / Part Number') }}</th>
                        <th>{{ __('Category') }}</th>
                        <th>{{ __('Total Stocked') }}</th>
                        <th>{{ __('Sold') }}</th>
                        <th>{{ __('Available') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if (! empty($product->image))
                                        <img src="{{ asset($product->image[0]) }}" alt="{{ $product->name }}"
                                            class="rounded" style="width:36px; height:36px; object-fit:cover;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center rounded bg-light"
                                            style="width:36px; height:36px;">
                                            <i class="fa-solid fa-image text-secondary small"></i>
                                        </div>
                                    @endif
                                    <a href="{{ route('products.show', $product) }}" class="fw-medium text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </div>
                            </td>
                            <td class="text-secondary">{{ $product->sku ?? '—' }}</td>
                            <td class="text-secondary">{{ $product->category->name ?? '—' }}</td>
                            <td class="text-secondary">{{ $product->total_qty + 0 }}</td>
                            <td class="text-secondary">{{ $product->sold_qty + 0 }}</td>
                            <td>
                                <span class="badge bg-danger">{{ ($product->total_qty - $product->sold_qty) + 0 }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fa-solid fa-box-open fs-2 text-secondary opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-0">{{ __('No products are out of stock.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="card-footer bg-white">
                {{ $products->links() }}
            </div>
        @endif
    </div>

@endsection
