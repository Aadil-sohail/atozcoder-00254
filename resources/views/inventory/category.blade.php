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
                <span class="text-muted small">{{ $products->count() }} {{ Str::plural('product', $products->count()) }} in this category</span>
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
                <tbody>
                    @foreach ($products as $product)
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    $(function () {
        $('#category-products-table').DataTable({
            columnDefs: [
                { orderable: false, targets: 'no-sort' },
            ],
            order: [],
        });
    });
</script>
@endpush
