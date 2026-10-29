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
                    {{ __('Current Stock') }}: {{ number_format($product->availableStock(), 2) }}
                </span>
                @if ($connected)
                    <div class="small text-muted mt-1">
                        <i class="fa-solid fa-link me-1"></i>{{ __('Connected product: the history of every connected product is shown, since they share one stock.') }}
                    </div>
                @endif
            </div>
            <a href="{{ $product->category_id ? route('inventories.category', $product->category_id) : route('inventories.index') }}" class="btn btn-dark btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i>{{ __('Back') }}
            </a>
        </div>

        {{-- Every stock movement of this product, written by App\Services\ProductStock. --}}
        <div class="table-responsive">
            <table id="stock-entries-table" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Date') }}</th>
                        @if ($connected)
                            <th>{{ __('Product') }}</th>
                        @endif
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Change') }}</th>
                        <th>{{ __('Stock After') }}</th>
                        <th>{{ __('Store') }}</th>
                        <th>{{ __('Reference') }}</th>
                        <th>{{ __('Note') }}</th>
                        <th>{{ __('By') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        @php
                            $colour = match ($log->type) {
                                \App\Models\InventoryLog::SALE, \App\Models\InventoryLog::EBAY_ORDER => 'primary',
                                \App\Models\InventoryLog::RETURN, \App\Models\InventoryLog::EBAY_RETURN => 'warning',
                                \App\Models\InventoryLog::RETURN_DELETED => 'danger',
                                \App\Models\InventoryLog::ADJUSTMENT => 'info',
                                default => 'success',
                            };
                        @endphp
                        <tr>
                            {{-- Sorted on the timestamp, not the formatted text. --}}
                            <td class="text-secondary" data-order="{{ $log->created_at->timestamp }}.{{ $log->id }}">
                                {{ $log->created_at->format('Y-m-d H:i') }}
                            </td>
                            @if ($connected)
                                <td class="fw-medium">{{ $log->product->name ?? '—' }}</td>
                            @endif
                            <td>
                                <span class="badge bg-{{ $colour }}-subtle text-{{ $colour }}-emphasis border border-{{ $colour }}-subtle">
                                    {{ $log->typeLabel() }}
                                </span>
                            </td>
                            <td data-order="{{ $log->quantity }}">
                                @if ($log->quantity >= 0)
                                    <span class="fw-semibold text-success">+{{ number_format((float) $log->quantity, 2) }}</span>
                                @else
                                    <span class="fw-semibold text-danger">{{ number_format((float) $log->quantity, 2) }}</span>
                                @endif
                            </td>
                            <td>{{ $log->balance_after === null ? '—' : number_format((float) $log->balance_after, 2) }}</td>
                            <td class="text-secondary">{{ $log->ebayAccount->store_name ?? '—' }}</td>
                            <td>
                                @if ($log->sale)
                                    <a href="{{ route('sales.show', $log->sale) }}" class="fw-medium text-decoration-none">{{ $log->sale->invoice_no }}</a>
                                @endif
                                @if ($log->sale_return_id)
                                    <div>
                                        <a href="{{ route('returns.show', $log->sale_return_id) }}" class="small text-decoration-none">
                                            {{ __('Return') }} #{{ $log->sale_return_id }}
                                        </a>
                                    </div>
                                @endif
                                @if (! $log->sale && ! $log->sale_return_id)
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-secondary small">{{ $log->note ?? '—' }}</td>
                            <td class="text-secondary">{{ $log->inserted_by ?? '—' }}</td>
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
