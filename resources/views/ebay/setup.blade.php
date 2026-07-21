@extends('layouts.header')

@section('title', 'eBay Store Setup')

@section('header')
    <h2 class="text-lg font-semibold leading-tight text-gray-800">
        {{ __('eBay Store Setup') }} — {{ $ebayAccount->store_name }}
    </h2>
@endsection

@section('content')

    @if ($loadError)
        <div class="alert alert-danger" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i>
            {{ __('Could not load data from eBay:') }} {{ $loadError }}
        </div>
    @endif

    <div class="card shadow-sm border-0 p-2">
        <div class="card-header bg-white py-3">
            <p class="mb-0 text-muted small">
                {{ __('eBay requires a fulfillment (shipping), payment and return policy plus a ship-from location before a listing can be published. Pick them below — they apply to every product synced to this store.') }}
            </p>
        </div>

        <div class="card-body">
            @foreach (['status' => 'success', 'info' => 'info', 'warning' => 'warning', 'error' => 'danger'] as $key => $type)
                @if (session($key))
                    <div class="alert alert-{{ $type }} small">{{ session($key) }}</div>
                @endif
            @endforeach

            @if ($errors->any())
                <div class="alert alert-danger small">
                    <ul class="mb-0 ps-3">
                        @foreach (array_unique($errors->all()) as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($policies['fulfillment'] === [] || $policies['payment'] === [] || $policies['return'] === [])
                <div class="alert alert-warning small d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <span>
                        {{ __('Some business policies are missing on this eBay account. Click the button to create a basic set automatically (free domestic shipping, 1-day handling, 30-day returns). You can refine them later on eBay.') }}
                    </span>
                    <form method="POST" action="{{ route('ebay.policies.default', $ebayAccount) }}" class="flex-shrink-0">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i class="fa-solid fa-wand-magic-sparkles me-1"></i>{{ __('Create default policies') }}
                        </button>
                    </form>
                </div>
            @endif

            <form method="POST" action="{{ route('ebay.setup.save', $ebayAccount) }}">
                @csrf

                <h6 class="fw-semibold mb-3">{{ __('Business Policies') }}</h6>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label small text-muted mb-1">{{ __('Fulfillment (shipping) policy') }}</label>
                        <select name="fulfillment_policy_id" class="form-select form-select-sm @error('fulfillment_policy_id') is-invalid @enderror" required>
                            <option value="">{{ __('Select…') }}</option>
                            @foreach ($policies['fulfillment'] as $policy)
                                <option value="{{ $policy['fulfillmentPolicyId'] }}"
                                    @selected(old('fulfillment_policy_id', $ebayAccount->fulfillment_policy_id) === $policy['fulfillmentPolicyId'])>
                                    {{ $policy['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('fulfillment_policy_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted mb-1">{{ __('Payment policy') }}</label>
                        <select name="payment_policy_id" class="form-select form-select-sm @error('payment_policy_id') is-invalid @enderror" required>
                            <option value="">{{ __('Select…') }}</option>
                            @foreach ($policies['payment'] as $policy)
                                <option value="{{ $policy['paymentPolicyId'] }}"
                                    @selected(old('payment_policy_id', $ebayAccount->payment_policy_id) === $policy['paymentPolicyId'])>
                                    {{ $policy['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_policy_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted mb-1">{{ __('Return policy') }}</label>
                        <select name="return_policy_id" class="form-select form-select-sm @error('return_policy_id') is-invalid @enderror" required>
                            <option value="">{{ __('Select…') }}</option>
                            @foreach ($policies['return'] as $policy)
                                <option value="{{ $policy['returnPolicyId'] }}"
                                    @selected(old('return_policy_id', $ebayAccount->return_policy_id) === $policy['returnPolicyId'])>
                                    {{ $policy['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('return_policy_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <h6 class="fw-semibold mb-3">{{ __('Ship-from Location') }}</h6>

                <div class="row g-3 mb-2">
                    <div class="col-md-4">
                        <label class="form-label small text-muted mb-1">{{ __('Existing inventory location') }}</label>
                        <select name="merchant_location_key" id="merchant_location_key" class="form-select form-select-sm @error('merchant_location_key') is-invalid @enderror">
                            <option value="">{{ __('— Create a new one below —') }}</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location['merchantLocationKey'] }}"
                                    @selected(old('merchant_location_key', $ebayAccount->merchant_location_key) === $location['merchantLocationKey'])>
                                    {{ $location['name'] ?? $location['merchantLocationKey'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('merchant_location_key')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div id="new-location-fields" class="border rounded p-3 bg-light-subtle mb-4">
                    <p class="text-muted small mb-2">{{ __('New location (the physical address you ship products from):') }}</p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-1">{{ __('Location name') }}</label>
                            <input type="text" name="new_location[name]" value="{{ old('new_location.name') }}" class="form-control form-control-sm" placeholder="{{ __('e.g. Main Warehouse') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-1">{{ __('Address line') }}</label>
                            <input type="text" name="new_location[address_line1]" value="{{ old('new_location.address_line1') }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-1">{{ __('City') }}</label>
                            <input type="text" name="new_location[city]" value="{{ old('new_location.city') }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-1">{{ __('State / Province') }}</label>
                            <input type="text" name="new_location[state]" value="{{ old('new_location.state') }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-1">{{ __('Postal code') }}</label>
                            <input type="text" name="new_location[postal_code]" value="{{ old('new_location.postal_code') }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-1">{{ __('Country (2-letter code)') }}</label>
                            <input type="text" name="new_location[country]" value="{{ old('new_location.country', 'US') }}" class="form-control form-control-sm" maxlength="2" style="text-transform:uppercase;">
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button type="submit" class="btn btn-dark btn-sm">
                        <i class="fa-solid fa-check me-1"></i>{{ __('Save Setup') }}
                    </button>
                    <a href="{{ route('ebay.index') }}" class="btn btn-outline-secondary btn-sm">{{ __('Back to Stores') }}</a>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Hide the new-location fields when an existing location is selected.
    (function () {
        const select = document.getElementById('merchant_location_key');
        const fields = document.getElementById('new-location-fields');
        const toggle = () => { fields.style.display = select.value ? 'none' : ''; };
        select.addEventListener('change', toggle);
        toggle();
    })();
</script>
@endpush
