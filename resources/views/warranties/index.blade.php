@extends('layouts.header')

@section('title', 'Warranties')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('Warranties') }}</h2>
@endsection

@section('content')

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('warranties.index') }}" class="row g-2 align-items-end">

                <div class="col-md-3">
                    <label for="customer_id" class="form-label small text-muted mb-1">{{ __('Customer') }}</label>
                    <select id="customer_id" name="customer_id" class="form-select form-select-sm">
                        <option value="">{{ __('All customers') }}</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" @selected(request('customer_id') == $customer->id)>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="product_id" class="form-label small text-muted mb-1">{{ __('Product') }}</label>
                    <select id="product_id" name="product_id" class="form-select form-select-sm">
                        <option value="">{{ __('All products') }}</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="expiry_from" class="form-label small text-muted mb-1">{{ __('Warranty Expiry From') }}</label>
                    <input id="expiry_from" name="expiry_from" type="date" value="{{ request('expiry_from') }}"
                        class="form-control form-control-sm">
                </div>

                <div class="col-md-2">
                    <label for="expiry_to" class="form-label small text-muted mb-1">{{ __('Warranty Expiry To') }}</label>
                    <input id="expiry_to" name="expiry_to" type="date" value="{{ request('expiry_to') }}"
                        class="form-control form-control-sm">
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm">
                        <i class="fa-solid fa-filter me-1"></i>{{ __('Filter') }}
                    </button>
                    <a href="{{ route('warranties.index') }}" class="btn btn-outline-secondary btn-sm">
                        {{ __('Reset') }}
                    </a>
                </div>

            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <p class="mb-0 text-muted small">{{ __('Products sold with a warranty and their remaining cover.') }}</p>
            <span class="text-muted" style="font-size:12px;">{{ $warranties->total() }} {{ Str::plural('record', $warranties->total()) }} total</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Invoice No') }}</th>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Product') }}</th>
                        <th>{{ __('Sale Date') }}</th>
                        <th>{{ __('Qty') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Expiry Date') }}</th>
                        <th>{{ __('Days Left') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($warranties as $item)
                        @php
                            $expiry = \Carbon\Carbon::parse($item->warranty_expiry)->startOfDay();
                            $daysLeft = (int) now()->startOfDay()->diffInDays($expiry, false);
                            $fullyReturned = $item->returned_qty >= $item->quantity;
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('sales.show', $item->sale_id) }}" class="fw-medium text-decoration-none">
                                    {{ $item->sale->invoice_no }}
                                </a>
                            </td>
                            <td class="text-secondary">{{ $item->sale->customer->name }}</td>
                            <td class="text-secondary">{{ $item->product->name }}</td>
                            <td class="text-secondary">{{ \Carbon\Carbon::parse($item->sale->sale_date)->format('M d, Y') }}</td>
                            <td class="text-secondary">
                                {{ ($item->quantity - $item->returned_qty) + 0 }}
                                @if ($item->returned_qty > 0)
                                    <span class="text-danger small">({{ $item->returned_qty + 0 }} {{ __('returned') }})</span>
                                @endif
                            </td>
                            <td>
                                @if ($fullyReturned)
                                    <span class="badge bg-danger">{{ __('Warranty Cancelled') }}</span>
                                @elseif ($daysLeft < 0)
                                    <span class="badge bg-secondary">{{ __('Warranty Ended') }}</span>
                                @else
                                    <span class="badge bg-success">{{ __('In Warranty') }}</span>
                                @endif
                            </td>
                            <td class="fw-medium">{{ $expiry->format('M d, Y') }}</td>
                            <td>
                                @if ($fullyReturned)
                                    <span class="text-muted">—</span>
                                @elseif ($daysLeft < 0)
                                    <span class="badge bg-danger">{{ __('Expired') }} {{ abs($daysLeft) }} {{ Str::plural('day', abs($daysLeft)) }} {{ __('ago') }}</span>
                                @elseif ($daysLeft === 0)
                                    <span class="badge bg-danger">{{ __('Expires today') }}</span>
                                @elseif ($daysLeft <= 30)
                                    <span class="badge bg-warning text-dark">{{ $daysLeft }} {{ Str::plural('day', $daysLeft) }} {{ __('left') }}</span>
                                @else
                                    <span class="badge bg-success">{{ $daysLeft }} {{ Str::plural('day', $daysLeft) }} {{ __('left') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fa-solid fa-shield-halved fs-2 text-secondary opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-0">{{ __('No warranty records found.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($warranties->hasPages())
            <div class="card-footer bg-white">
                {{ $warranties->links() }}
            </div>
        @endif
    </div>

@endsection
