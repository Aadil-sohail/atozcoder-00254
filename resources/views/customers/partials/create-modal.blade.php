<div class="modal fade {{ $errors->any() && !old('_edit_customer_id') ? 'show d-block' : '' }}"
    id="create-customer-modal" tabindex="-1" aria-labelledby="createCustomerLabel" aria-modal="true"
    style="{{ $errors->any() && !old('_edit_customer_id') ? 'background:rgba(0,0,0,0.5)' : '' }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('customers.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="createCustomerLabel">{{ __('Add Customer') }}</h5>
                    <button type="button" class="btn-close" onclick="closeCustomerModal('create-customer-modal')"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">{{ __('Name') }} <span class="text-danger">*</span></label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="off"
                                class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">{{ __('Phone') }}</label>
                            <input id="phone" name="phone" type="text" value="{{ old('phone') }}" autocomplete="off"
                                class="form-control @error('phone') is-invalid @enderror">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="off"
                                class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="address" class="form-label">{{ __('Address') }}</label>
                            <textarea id="address" name="address" rows="3" autocomplete="off"
                                class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeCustomerModal('create-customer-modal')" class="btn btn-outline-secondary">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-dark">{{ __('Create Customer') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
