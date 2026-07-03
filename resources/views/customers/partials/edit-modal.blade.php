@php $editErrors = $errors->any() && old('_edit_customer_id'); @endphp

<div class="modal fade {{ $editErrors ? 'show d-block' : '' }}"
    id="edit-customer-modal" tabindex="-1" aria-labelledby="editCustomerLabel" aria-modal="true"
    style="{{ $editErrors ? 'background:rgba(0,0,0,0.5)' : '' }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="edit_customer_form"
                action="{{ old('_edit_customer_id') ? route('customers.update', old('_edit_customer_id')) : '' }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="_edit_customer_id" id="edit_hidden_customer_id" value="{{ old('_edit_customer_id') }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="editCustomerLabel">{{ __('Edit Customer') }}</h5>
                    <button type="button" class="btn-close" onclick="closeCustomerModal('edit-customer-modal')"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_name" class="form-label">{{ __('Name') }} <span class="text-danger">*</span></label>
                            <input id="edit_name" name="name" type="text" required autocomplete="off"
                                value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="edit_phone" class="form-label">{{ __('Phone') }}</label>
                            <input id="edit_phone" name="phone" type="text" autocomplete="off"
                                value="{{ old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="edit_email" class="form-label">{{ __('Email') }}</label>
                            <input id="edit_email" name="email" type="email" autocomplete="off"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="edit_address" class="form-label">{{ __('Address') }}</label>
                            <textarea id="edit_address" name="address" rows="3" autocomplete="off"
                                class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeCustomerModal('edit-customer-modal')" class="btn btn-outline-secondary">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-dark">{{ __('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
