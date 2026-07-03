<div class="modal fade {{ $errors->any() && !old('_edit_role_id') ? 'show d-block' : '' }}"
    id="create-role-modal" tabindex="-1" aria-labelledby="createRoleLabel" aria-modal="true"
    style="{{ $errors->any() && !old('_edit_role_id') ? 'background:rgba(0,0,0,0.5)' : '' }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('roles.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="createRoleLabel">{{ __('Add Role') }}</h5>
                    <button type="button" class="btn-close" onclick="closeRoleModal('create-role-modal')"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Role Name') }}</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                            class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <label class="form-label">{{ __('Permissions') }}</label>
                    @include('roles.partials.permission-checkboxes', ['prefix' => 'create', 'checkedIds' => old('permissions', [])])
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeRoleModal('create-role-modal')" class="btn btn-outline-secondary">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-dark">{{ __('Create Role') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
