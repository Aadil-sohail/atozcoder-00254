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
            {{-- Filtering reloads the grid in place rather than the page; the
                 values ride along with every draw (see the script below). --}}
            <form id="warranty-filters" method="GET" action="{{ route('warranties.index') }}" class="row g-2 align-items-end">

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
                    <button type="reset" class="btn btn-outline-secondary btn-sm">
                        {{ __('Reset') }}
                    </button>
                </div>

            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <p class="mb-0 text-muted small">{{ __('Products sold with a warranty and their remaining cover.') }}</p>
            <span class="text-muted" style="font-size:12px;" id="warranties-count">{{ $recordCount }} {{ Str::plural('record', $recordCount) }} total</span>
        </div>

        <div class="table-responsive">
            <table id="warranties-table" class="table table-hover align-middle mb-0">
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
                <tbody></tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Rows are fetched a page at a time — see App\Support\ServerTable. The
    // filter form's values are sent with every draw, so paging and filtering
    // stay in step instead of the filter applying to one page only.
    $(function () {
        const filters = document.getElementById('warranty-filters');

        const table = serverTable('#warranties-table', {
            url: @json(route('warranties.data')),
            columns: [
                { data: 'invoice_no' },
                { data: 'customer_name' },
                { data: 'product_name' },
                { data: 'sale_date' },
                { data: 'qty', searchable: false },
                { data: 'status', orderable: false, searchable: false },
                { data: 'expiry', searchable: false },
                { data: 'days_left', searchable: false },
            ],
            // Soonest to expire first, as before.
            order: [[6, 'asc']],
            ajax: {
                data: function (params) {
                    new FormData(filters).forEach(function (value, key) {
                        params[key] = value;
                    });
                },
            },
        });

        // The count in the header describes the filtered set, so it is
        // refreshed from whatever the server just reported.
        table.on('draw', function () {
            const total = table.page.info().recordsTotal;
            document.getElementById('warranties-count').textContent =
                total + ' ' + (total === 1 ? @json(__('record')) : @json(__('records'))) + ' ' + @json(__('total'));
        });

        filters.addEventListener('submit', function (e) {
            e.preventDefault();
            table.ajax.reload();
        });

        // reset() clears the fields after this event, so reload once it has.
        filters.addEventListener('reset', function () {
            setTimeout(() => table.ajax.reload(), 0);
        });
    });
</script>
@endpush
