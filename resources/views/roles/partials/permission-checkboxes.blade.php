@php
    $checkedIds = $checkedIds ?? [];
@endphp

<div class="row g-3">
    @foreach ($permissionsByModule as $module => $permissions)
        <div class="col-md-6">
            <div class="border rounded p-2 h-100">
                <div class="fw-semibold small text-uppercase text-muted mb-2">{{ Str::title($module) }}</div>
                <div class="d-flex flex-wrap gap-3">
                    @foreach ($permissions as $permission)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                name="permissions[]"
                                value="{{ $permission->id }}"
                                id="perm_{{ $prefix }}_{{ $permission->id }}"
                                {{ in_array($permission->id, $checkedIds) ? 'checked' : '' }}>
                            <label class="form-check-label small" for="perm_{{ $prefix }}_{{ $permission->id }}">
                                {{ Str::title(Str::before($permission->name, ' ')) }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
