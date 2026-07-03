@extends('layouts.header')

@section('title', 'Roles & Permissions')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('Roles & Permissions') }}</h2>
@endsection

@section('content')

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 p-2">
        <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
            <div>
                <p class="mb-0 text-muted small">{{ __('Manage roles and the permissions granted to each one.') }}</p>
                <span class="text-muted" style="font-size:12px;">{{ $roles->total() }} {{ Str::plural('role', $roles->total()) }} total</span>
            </div>
            @can('create roles')
            <button type="button" onclick="openRoleModal('create-role-modal')" class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                {{ __('Add Role') }}
            </button>
            @endcan
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Permissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <td class="fw-medium">{{ $role->name }}</td>
                            <td class="text-secondary">{{ $role->permissions_count }} {{ Str::plural('permission', $role->permissions_count) }}</td>
                            <td class="text-start">
                                <div class="d-flex align-items-left justify-content-start gap-2">
                                    @can('edit roles')
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="openRoleModal('edit-role-modal-{{ $role->id }}')">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    @endcan
                                    @can('delete roles')
                                        @unless ($role->name === 'Admin')
                                            <form method="POST" action="{{ route('roles.destroy', $role) }}" class="d-inline"
                                                onsubmit="return confirm('{{ __('Are you sure you want to delete this role?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endunless
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <i class="fa-solid fa-user-shield fs-2 text-secondary opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-2">{{ __('No roles found.') }}</p>
                                @can('create roles')
                                    <button type="button" onclick="openRoleModal('create-role-modal')" class="btn btn-sm btn-dark">
                                        {{ __('Add your first role') }}
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($roles->hasPages())
            <div class="card-footer bg-white">
                {{ $roles->links() }}
            </div>
        @endif
    </div>

    @include('roles.partials.create-modal')
    @foreach ($roles as $role)
        @include('roles.partials.edit-modal', ['role' => $role])
    @endforeach

    @push('scripts')
    <script>
        function openRoleModal(id) {
            new bootstrap.Modal(document.getElementById(id)).show();
        }

        function closeRoleModal(id) {
            const el = document.getElementById(id);
            (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).hide();
        }
    </script>
    @endpush

@endsection
