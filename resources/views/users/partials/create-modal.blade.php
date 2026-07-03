<div class="modal fade {{ $errors->any() && !old('_edit_user_id') ? 'show d-block' : '' }}"
    id="create-user-modal" tabindex="-1" aria-labelledby="createUserLabel" aria-modal="true"
    style="{{ $errors->any() && !old('_edit_user_id') ? 'background:rgba(0,0,0,0.5)' : '' }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="createUserLabel">{{ __('Add User') }}</h5>
                    <button type="button" class="btn-close" onclick="closeUserModal('create-user-modal')"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                                class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="username" class="form-label">{{ __('Username') }}</label>
                            <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus autocomplete="username"
                                class="form-control @error('username') is-invalid @enderror">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                                class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" name="password" type="password" required autocomplete="new-password"
                                class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                                class="form-control @error('password_confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="role" class="form-label">{{ __('Role') }}</label>
                            <select id="role" name="role" class="form-select @error('role') is-invalid @enderror">
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
                    <button type="button" onclick="closeUserModal('create-user-modal')" class="btn btn-outline-secondary">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-dark">{{ __('Create User') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
