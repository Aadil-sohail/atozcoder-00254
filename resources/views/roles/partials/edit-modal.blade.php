@php
    $showModal = $errors->any() && old('_edit_role_id') == $role->id;
    $checkedIds = $showModal ? old('permissions', []) : $role->permissions->pluck('id')->toArray();
@endphp

<div class="modal fade {{ $showModal ? 'show d-block' : '' }}"
    id="edit-role-modal-{{ $role->id }}" tabindex="-1" aria-labelledby="editRoleLabel{{ $role->id }}" aria-modal="true"
    style="{{ $showModal ? 'background:rgba(0,0,0,0.5)' : '' }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('roles.update', $role) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="_edit_role_id" value="{{ $role->id }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleLabel{{ $role->id }}">{{ __('Edit Role') }}</h5>
                    <button type="button" class="btn-close" onclick="closeRoleModal('edit-role-modal-{{ $role->id }}')"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name_{{ $role->id }}" class="form-label">{{ __('Role Name') }}</label>
                        <input id="edit_name_{{ $role->id }}" name="name" type="text"
                            value="{{ $showModal ? old('name') : $role->name }}" required
                            class="form-control @error('name') is-invalid @enderror">
                        @if ($showModal)
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>

                    <label class="form-label">{{ __('Permissions') }}</label>
                    @include('roles.partials.permission-checkboxes', ['prefix' => 'edit_'.$role->id, 'checkedIds' => $checkedIds])
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeRoleModal('edit-role-modal-{{ $role->id }}')" class="btn btn-outline-secondary">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-dark">{{ __('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
