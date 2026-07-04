@extends('layouts.header')

@section('title', 'Sale ' . $sale->invoice_no)

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('Sale') }}: {{ $sale->invoice_no }}</h2>
@endsection

@section('content')

{{-- Sale Info --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
        <span class="fw-semibold">{{ __('Sale Info') }}</span>
        <div class="d-flex gap-2">
            <form method="POST" action="{{ route('sales.destroy', $sale) }}"
                onsubmit="return confirmDelete(event, 'Delete this sale? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="fa-solid fa-trash me-1"></i>{{ __('Delete') }}
                </button>
            </form>
            <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i>{{ __('Back') }}
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <p class="text-muted small mb-1">{{ __('Invoice No') }}</p>
                <p class="fw-semibold mb-0">{{ $sale->invoice_no }}</p>
            </div>
            <div class="col-md-3">
                <p class="text-muted small mb-1">{{ __('Customer') }}</p>
                <p class="fw-semibold mb-0">{{ $sale->customer->name }}</p>
            </div>
            <div class="col-md-3">
                <p class="text-muted small mb-1">{{ __('Sale Date') }}</p>
                <p class="fw-semibold mb-0">{{ \Carbon\Carbon::parse($sale->sale_date)->format('M d, Y') }}</p>
            </div>
            <div class="col-md-3">
                <p class="text-muted small mb-1">{{ __('Total') }}</p>
                <p class="fw-bold fs-5 text-success mb-0">{{ number_format($sale->total_amount, 2) }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Items --}}
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <span class="fw-semibold">{{ __('Items') }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>{{ __('Product') }}</th>
                    <th class="text-left">{{ __('Qty') }}</th>
                    <th class="text-left">{{ __('Returned') }}</th>
                    <th class="text-left">{{ __('Unit Price') }}</th>
                    <th class="text-left">{{ __('Subtotal') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->saleItems as $i => $item)
                    <tr>
                        <td class="text-muted">{{ $i + 1 }}</td>
                        <td class="fw-medium">
                            {{ $item->product->name }}
                            @if($item->product->sku)
                                <span class="text-muted small">({{ $item->product->sku }})</span>
                            @endif
                        </td>
                        <td class="text-left">{{ number_format($item->quantity, 2) }}</td>
                        <td class="text-left {{ $item->returned_qty > 0 ? 'text-danger fw-medium' : 'text-muted' }}">
                            {{ number_format($item->returned_qty, 2) }}
                        </td>
                        <td class="text-left">{{ number_format($item->selling_price, 2) }}</td>
                        <td class="text-left fw-medium">
                            @if ($item->returned_qty > 0)
                                <del class="text-muted fw-normal small">{{ number_format($item->subtotal, 2) }}</del>
                                {{ number_format(($item->quantity - $item->returned_qty) * $item->selling_price, 2) }}
                            @else
                                {{ number_format($item->subtotal, 2) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <td colspan="5" class="text-end text-muted">{{ __('Subtotal') }}</td>
                    <td class="text-left fw-medium">{{ number_format($sale->saleItems->sum(fn ($i) => ($i->quantity - $i->returned_qty) * $i->selling_price), 2) }}</td>
                </tr>
                @if($sale->discount > 0)
                <tr>
                    <td colspan="5" class="text-end text-muted">{{ __('Discount') }}</td>
                    <td class="text-left text-danger">- {{ number_format($sale->discount, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td colspan="5" class="text-end fw-semibold">{{ __('Total') }}</td>
                    <td class="text-left fw-bold text-success fs-6">{{ number_format($sale->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection
