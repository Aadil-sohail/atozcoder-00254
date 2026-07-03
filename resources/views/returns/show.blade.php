@extends('layouts.header')

@section('title', 'Return - ' . $saleReturn->sale->invoice_no)

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('Return') }}: {{ $saleReturn->sale->invoice_no }}</h2>
@endsection

@section('content')

{{-- Return Info --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
        <span class="fw-semibold">{{ __('Return Info') }}</span>
        <div class="d-flex gap-2">
            @can('delete returns')
            <form method="POST" action="{{ route('returns.destroy', $saleReturn) }}"
                onsubmit="return confirmDelete(event, {{ json_encode(__('Delete this return? Any restocked quantity will be removed again.')) }});">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="fa-solid fa-trash me-1"></i>{{ __('Delete') }}
                </button>
            </form>
            @endcan
            <a href="{{ route('returns.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i>{{ __('Back') }}
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <p class="text-muted small mb-1">{{ __('Invoice No') }}</p>
                <p class="fw-semibold mb-0">{{ $saleReturn->sale->invoice_no }}</p>
            </div>
            <div class="col-md-3">
                <p class="text-muted small mb-1">{{ __('Customer') }}</p>
                <p class="fw-semibold mb-0">{{ $saleReturn->sale->customer->name }}</p>
            </div>
            <div class="col-md-3">
                <p class="text-muted small mb-1">{{ __('Return Date') }}</p>
                <p class="fw-semibold mb-0">{{ \Carbon\Carbon::parse($saleReturn->return_date)->format('M d, Y') }}</p>
            </div>
            <div class="col-md-3">
                <p class="text-muted small mb-1">{{ __('Recorded By') }}</p>
                <p class="fw-semibold mb-0">{{ $saleReturn->inserted_by ?? '—' }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Items --}}
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <span class="fw-semibold">{{ __('Returned Items') }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>{{ __('Product') }}</th>
                    <th class="text-left">{{ __('Qty') }}</th>
                    <th class="text-left">{{ __('Condition') }}</th>
                    <th class="text-left">{{ __('Restocked') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($saleReturn->items as $i => $item)
                    <tr>
                        <td class="text-muted">{{ $i + 1 }}</td>
                        <td class="fw-medium">
                            {{ $item->product->name }}
                            @if($item->product->sku)
                                <span class="text-muted small">({{ $item->product->sku }})</span>
                            @endif
                        </td>
                        <td class="text-left">{{ number_format($item->quantity, 2) }}</td>
                        <td class="text-left">
                            <span class="badge {{ $item->condition === 'good' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($item->condition) }}
                            </span>
                        </td>
                        <td class="text-left">
                            @if($item->condition === 'good')
                                <span class="text-success"><i class="fa-solid fa-circle-check me-1"></i>{{ __('Yes') }}</span>
                            @else
                                <span class="text-muted">{{ __('No') }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
