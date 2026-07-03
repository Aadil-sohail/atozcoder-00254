@extends('layouts.header')

@section('title', 'Returns')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('Returns') }}</h2>
@endsection

@section('content')

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
            <div>
                <p class="mb-0 text-muted small">{{ __('Sale returns recorded across all invoices.') }}</p>
                <span class="text-muted" style="font-size:12px;">{{ $returns->total() }} {{ Str::plural('return', $returns->total()) }} total</span>
            </div>
            @can('create returns')
            <a href="{{ route('returns.create') }}" class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                {{ __('New Return') }}
            </a>
            @endcan
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Invoice No') }}</th>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Return Date') }}</th>
                        <th>{{ __('Items') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($returns as $return)
                        <tr>
                            <td>
                                <a href="{{ route('returns.show', $return) }}" class="fw-medium text-decoration-none">
                                    {{ $return->sale->invoice_no }}
                                </a>
                            </td>
                            <td class="text-secondary">{{ $return->sale->customer->name }}</td>
                            <td class="text-secondary">{{ \Carbon\Carbon::parse($return->return_date)->format('M d, Y') }}</td>
                            <td class="text-secondary">{{ $return->items->count() }} {{ Str::plural('item', $return->items->count()) }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('returns.show', $return) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @can('delete returns')
                                    <form method="POST" action="{{ route('returns.destroy', $return) }}" class="d-inline"
                                        onsubmit="return confirmDelete(event, {{ json_encode(__('Delete this return? Any restocked quantity will be removed again.')) }});">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fa-solid fa-rotate-left fs-2 text-secondary opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-2">{{ __('No returns recorded yet.') }}</p>
                                @can('create returns')
                                    <a href="{{ route('returns.create') }}" class="btn btn-sm btn-dark">
                                        {{ __('Record a return') }}
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($returns->hasPages())
            <div class="card-footer bg-white">
                {{ $returns->links() }}
            </div>
        @endif
    </div>

@endsection
