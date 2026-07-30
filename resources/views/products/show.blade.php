@extends('layouts.header')

@section('title', 'Product Details')

@section('header')
    <h2 class="text-lg font-semibold leading-tight text-gray-800">
        {{ __('Product Details') }}
    </h2>
@endsection

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
            <div class="d-flex align-items-center gap-3">
                @if (! empty($product->image))
                    <img src="{{ asset($product->image[0]) }}" alt="{{ $product->name }}"
                        class="rounded" style="width:64px; height:64px; object-fit:cover;">
                @else
                    <div class="d-flex align-items-center justify-content-center rounded bg-light"
                        style="width:64px; height:64px;">
                        <i class="fa-solid fa-image fs-4 text-secondary"></i>
                    </div>
                @endif

                <div>
                    <h5 class="mb-1 fw-semibold">{{ $product->name }}</h5>
                    <span class="badge {{ $product->status === '1' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $product->status === '1' ? __('Active') : __('Inactive') }}
                    </span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @can('edit products')
                <a href="{{ route('products.edit', $product) }}" class="btn btn-dark btn-sm">
                    <i class="fa-solid fa-pen me-1"></i>{{ __('Edit') }}
                </a>
                @endcan
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i>{{ __('Back') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-4">

                <div class="col-md-6">
                    <p class="text-muted small mb-1">{{ __('SKU / Part Number') }}</p>
                    <p class="fw-medium mb-0">{{ $product->sku ?? '—' }}</p>
                </div>

                <div class="col-md-6">
                    <p class="text-muted small mb-1">{{ __('Variant') }}</p>
                    <p class="fw-medium mb-0">{{ $product->variant ?? '—' }}</p>
                </div>

                <div class="col-md-6">
                    <p class="text-muted small mb-1">{{ __('Category') }}</p>
                    <p class="fw-medium mb-0">{{ $product->category->name ?? '—' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small mb-1">{{ __('Subcategory') }}</p>
                    <p class="fw-medium mb-0">{{ $product->subcategory->name ?? '—' }}</p>
                </div>

                <div class="col-md-6">
                    <p class="text-muted small mb-1">{{ __('Size') }}</p>
                    <p class="fw-medium mb-0">{{ $product->size ?? '—' }}</p>
                </div>

                <div class="col-md-6">
                    <p class="text-muted small mb-1">{{ __('Cost Price') }}</p>
                    <p class="fw-medium mb-0">{{ $product->cost_price !== null ? number_format($product->cost_price, 2) : '—' }}</p>
                </div>

                <div class="col-md-6">
                    <p class="text-muted small mb-1">{{ __('Selling Price') }}</p>
                    <p class="fw-medium mb-0">{{ $product->selling_price !== null ? number_format($product->selling_price, 2) : '—' }}</p>
                </div>

                <div class="col-md-6">
                    <p class="text-muted small mb-1">{{ __('Unit Price') }}</p>
                    <p class="fw-medium mb-0">{{ $product->unit_price !== null ? number_format($product->unit_price, 2) : '—' }}</p>
                </div>

                <div class="col-md-6">
                    <p class="text-muted small mb-1">{{ __('Warranty') }}</p>
                    <p class="fw-medium mb-0">
                        @if ($product->warranty_months)
                            {{ $product->warranty_months == 12 ? __('1 Year') : $product->warranty_months . ' ' . ($product->warranty_months == 1 ? __('Month') : __('Months')) }}
                            <span class="text-muted">({{ __('expires') }} {{ $product->warranty_expiry_date?->format('d M, Y') }})</span>
                        @else
                            —
                        @endif
                    </p>
                </div>

                <div class="col-12">
                    <p class="text-muted small mb-1">{{ __('Description') }}</p>
                    <p class="mb-0" style="white-space: pre-line;">{{ $product->description ?? '—' }}</p>
                </div>

                @if (! empty($product->image))
                    <div class="col-12">
                        <p class="text-muted small mb-2">{{ __('Photos') }}</p>
                        <div class="row g-2">
                            @foreach ($product->image as $path)
                                <div class="col-4 col-sm-3 col-md-2">
                                    <img src="{{ asset($path) }}" alt="{{ $product->name }}"
                                        class="img-thumbnail w-100" style="height:96px; object-fit:cover;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection
