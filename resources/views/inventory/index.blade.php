@extends('layouts.header')

@section('title', 'Inventory')

@section('header')
    <h2 class="text-lg font-semibold leading-tight text-gray-800">
        {{ __('Inventory') }}
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
                <p class="mb-0 text-muted small">{{ __('Stock added to products over time.') }}</p>
            </div>
            @can('create inventories')
            <button type="button" onclick="openStockModal()" class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                {{ __('Add Stock') }}
            </button>
            @endcan
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
                    @forelse ($inventories as $inventory)
                        <tr>
                            <td class="fw-medium">{{ $inventory->product->name ?? '—' }}</td>
                            @php $available = ($inventory->product->total_qty ?? 0) - ($inventory->product->sold_qty ?? 0); @endphp
                            <td class="{{ $available <= 0 ? 'text-danger fw-semibold' : '' }}">
                                {{ number_format($available, 2) }}
                            </td>
                            <td>
                                <a href="{{ route('inventories.show', $inventory->product) }}"
                                    class="btn btn-sm btn-outline-secondary" title="{{ __('View') }}">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fa-solid fa-warehouse fs-2 text-secondary opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-2">{{ __('No stock entries found.') }}</p>
                                @can('create inventories')
                                    <button type="button" onclick="openStockModal()" class="btn btn-sm btn-dark">
                                        {{ __('Add your first stock entry') }}
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($inventories->hasPages())
            <div class="card-footer bg-white">
                {{ $inventories->links() }}
            </div>
        @endif
    </div>

    @include('inventory.partials.create-modal')

    @push('scripts')
    <script>
        function openStockModal() {
            new bootstrap.Modal(document.getElementById('create-inventory-modal')).show();
        }

        function closeStockModal() {
            const el = document.getElementById('create-inventory-modal');
            (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).hide();
        }
    </script>
    @endpush

@endsection
