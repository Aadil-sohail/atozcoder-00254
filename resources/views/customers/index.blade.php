@extends('layouts.header')

@section('title', 'Customers')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('Customers') }}</h2>
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
                <p class="mb-0 text-muted small">{{ __('Manage your customer records.') }}</p>
                <span class="text-muted" style="font-size:12px;">{{ $customers->count() }} {{ Str::plural('customer', $customers->count()) }} total</span>
            </div>
            @can('create customers')
            <button type="button" onclick="openCustomerModal('create-customer-modal')" class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                {{ __('Add Customer') }}
            </button>
            @endcan
        </div>

        <div class="table-responsive">
            <table id="customers-table" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Address') }}</th>
                        <th>{{ __('Added') }}</th>
                        <th class="text-end no-sort">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td class="fw-medium">{{ $customer->name }}</td>
                            <td class="text-secondary">{{ $customer->email ?? '—' }}</td>
                            <td class="text-secondary">{{ $customer->phone ?? '—' }}</td>
                            <td class="text-secondary" style="max-width:220px;">
                                <span class="d-inline-block text-truncate" style="max-width:200px;" title="{{ $customer->address }}">
                                    {{ $customer->address ?? '—' }}
                                </span>
                            </td>
                            <td class="text-secondary" data-order="{{ $customer->created_at?->timestamp ?? 0 }}">{{ $customer->created_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="text-end">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    @can('edit customers')
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="openEditModal(
                                            {{ $customer->id }},
                                            {{ json_encode($customer->name) }},
                                            {{ json_encode($customer->email) }},
                                            {{ json_encode($customer->phone) }},
                                            {{ json_encode($customer->address) }}
                                        )">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    @endcan
                                    @can('delete customers')
                                    <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="d-inline"
                                        onsubmit="return confirm('{{ __('Are you sure you want to delete this customer?') }}');">
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('customers.partials.create-modal')
    @include('customers.partials.edit-modal')

    @push('scripts')
    <script>
        $(function () {
            $('#customers-table').DataTable({
                columnDefs: [
                    { orderable: false, targets: 'no-sort' },
                ],
                order: [],
            });
        });

        function openCustomerModal(id) {
            new bootstrap.Modal(document.getElementById(id)).show();
        }

        function closeCustomerModal(id) {
            const el = document.getElementById(id);
            (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).hide();
        }

        function openEditModal(id, name, email, phone, address) {
            document.getElementById('edit_name').value    = name;
            document.getElementById('edit_email').value   = email ?? '';
            document.getElementById('edit_phone').value   = phone ?? '';
            document.getElementById('edit_address').value = address ?? '';
            document.getElementById('edit_hidden_customer_id').value = id;
            document.getElementById('edit_customer_form').action = '/customers/' + id;
            openCustomerModal('edit-customer-modal');
        }
    </script>
    @endpush

@endsection
