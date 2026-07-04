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
                <span class="text-muted small">{{ $products->total() }} {{ Str::plural('product', $products->total()) }} in this category</span>
            </div>
            <a href="{{ route('inventories.index') }}" class="btn btn-dark btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i>{{ __('Back') }}
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Product') }}</th>
                        <th>{{ __('Stock') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="fw-medium">{{ $product->name }}</td>
                            @php $available = $product->total_qty - $product->sold_qty; @endphp
                            <td class="{{ $available <= 0 ? 'text-danger fw-semibold' : '' }}">
                                {{ number_format($available, 2) }}
                            </td>
                            <td>
                                <a href="{{ route('inventories.show', $product) }}"
                                    class="btn btn-sm btn-outline-secondary" title="{{ __('View') }}">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <i class="fa-solid fa-box fs-2 text-secondary opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-0">{{ __('No products in this category.') }}</p>
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
