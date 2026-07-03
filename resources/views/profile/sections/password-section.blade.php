<!-- Password Tab -->
<div class="tab-pane fade" id="navs-pills-top-password" role="tabpanel">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Password</h5>
                    <form id="formChangePassword" method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        @if (session('status') === 'password-updated')
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Password updated successfully!
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label for="currentPassword" class="form-label">Current Password <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" id="currentPassword"
                                        name="current_password" placeholder="Enter your current password" />
                                    <span class="input-group-text cursor-pointer" id="toggleCurrentPassword">
                                        <i class="ti ti-eye-off" id="currentPasswordToggleIcon"></i>
                                    </span>
                                </div>
                                <div id="password-check-message" class="small mt-1"></div>
                                @error('current_password', 'updatePassword')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="newPassword" class="form-label">New Password <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" id="newPassword" name="password"
                                        placeholder="Enter your new password" />
                                    <span class="input-group-text cursor-pointer" id="toggleNewPassword">
                                        <i class="ti ti-eye-off" id="newPasswordToggleIcon"></i>
                                    </span>
                                </div>
                                @error('password', 'updatePassword')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="confirmPassword" class="form-label">Confirm Password <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" id="confirmPassword"
                                        name="password_confirmation" placeholder="Confirm your new password" />
                                    <span class="input-group-text cursor-pointer" id="toggleConfirmPassword">
                                        <i class="ti ti-eye-off" id="confirmPasswordToggleIcon"></i>
                                    </span>
                                </div>
                                @error('password_confirmation', 'updatePassword')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Update</button>
                            <button type="reset" class="btn btn-label-secondary">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Password Tab -->
