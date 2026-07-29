@extends('layouts.header')

@section('title', 'Stock Entries')

@section('header')
    <h2 class="text-lg font-semibold leading-tight text-gray-800">
        {{ __('Stock Entries') }}
    </h2>
@endsection

@section('content')

    <div class="card shadow-sm border-0 p-2">
        <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
            <div>
                <h5 class="mb-1 fw-semibold">{{ $product->name }}</h5>
                <span class="text-muted small">
                    {{ __('SKU') }}: {{ $product->sku ?? '—' }}
                    &middot;
                    {{ __('Current Stock') }}: {{ number_format($product->total_qty, 2) }}
                </span>
            </div>
            <a href="{{ $product->category_id ? route('inventories.category', $product->category_id) : route('inventories.index') }}" class="btn btn-dark btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i>{{ __('Back') }}
            </a>
        </div>

        <div class="table-responsive">
            <table id="stock-entries-table" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Quantity Added</th>
                        <th>Added By</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entries as $entry)
                        <tr>
                            <td class="fw-medium">{{ number_format($entry->quantity, 2) }}</td>
                            <td class="text-secondary">{{ $entry->inserted_by ?? '—' }}</td>
                            <td class="text-secondary">{{ $entry->created_at->format('Y-m-d H:i') }}</td>
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
        $('#stock-entries-table').DataTable({
            order: [],
        });
    });
</script>
@endpush
