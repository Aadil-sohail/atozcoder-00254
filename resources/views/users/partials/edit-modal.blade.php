@php $editErrors = $errors->any() && old('_edit_user_id'); @endphp

<div class="modal fade {{ $editErrors ? 'show d-block' : '' }}"
    id="edit-user-modal" tabindex="-1" aria-labelledby="editUserLabel" aria-modal="true"
    style="{{ $editErrors ? 'background:rgba(0,0,0,0.5)' : '' }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="edit_user_form"
                action="{{ old('_edit_user_id') ? route('users.update', old('_edit_user_id')) : '' }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="_edit_user_id" id="edit_hidden_user_id" value="{{ old('_edit_user_id') }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="editUserLabel">{{ __('Edit User') }}</h5>
                    <button type="button" class="btn-close" onclick="closeUserModal('edit-user-modal')"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_name" class="form-label">{{ __('Name') }}</label>
                            <input id="edit_name" name="name" type="text" required autocomplete="off"
                                value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="edit_username" class="form-label">{{ __('Username') }}</label>
                            <input id="edit_username" name="username" type="text" required autocomplete="off"
                                value="{{ old('username') }}"
                                class="form-control @error('username') is-invalid @enderror">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="edit_email" class="form-label">{{ __('Email') }}</label>
                            <input id="edit_email" name="email" type="email" required autocomplete="off"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="edit_password" class="form-label">
                                {{ __('New Password') }} <span class="text-muted small">({{ __('leave blank to keep current') }})</span>
                            </label>
                            <input id="edit_password" name="password" type="password" autocomplete="new-password"
                                class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="edit_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                            <input id="edit_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                                class="form-control @error('password_confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="edit_role" class="form-label">{{ __('Role') }}</label>
                            <select id="edit_role" name="role" class="form-select @error('role') is-invalid @enderror">
                                <option value="">{{ __('No role') }}</option>
                                @foreach ($roles as $roleName)
                                    <option value="{{ $roleName }}" {{ old('role') === $roleName ? 'selected' : '' }}>{{ $roleName }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeUserModal('edit-user-modal')" class="btn btn-outline-secondary">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-dark">{{ __('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
