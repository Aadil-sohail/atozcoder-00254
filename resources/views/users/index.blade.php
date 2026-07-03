@extends('layouts.header')

@section('title', 'Users')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('Users') }}</h2>
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
                <p class="mb-0 text-muted small">{{ __('Manage the user accounts that can access the system.') }}</p>
                <span class="text-muted" style="font-size:12px;">{{ $users->total() }} {{ Str::plural('user', $users->total()) }} total</span>
            </div>
            @can('create users')
            <button type="button" onclick="openUserModal('create-user-modal')" class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                {{ __('Add User') }}
            </button>
            @endcan
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="fw-medium">{{ $user->name }}</td>
                            <td class="text-secondary">{{ $user->username ?? '—' }}</td>
                            <td class="text-secondary">{{ $user->email }}</td>
                            <td class="text-secondary">{{ $user->getRoleNames()->implode(', ') ?: '—' }}</td>
                            <td class="text-start">
                                <div class="d-flex align-items-left justify-content-start gap-2">
                                    @can('edit users')
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="openEditModal({{ $user->id }}, {{ json_encode($user->name) }}, {{ json_encode($user->username) }}, {{ json_encode($user->email) }}, {{ json_encode($user->getRoleNames()->first()) }})">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    @endcan
                                    @can('delete users')
                                        @unless ($user->is(auth()->user()))
                                            <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline"
                                                onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}');">
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
                            <td colspan="6" class="text-center py-5">
                                <i class="fa-solid fa-users fs-2 text-secondary opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-2">{{ __('No users found.') }}</p>
                                @can('create users')
                                    <button type="button" onclick="openUserModal('create-user-modal')" class="btn btn-sm btn-dark">
                                        {{ __('Add your first user') }}
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="card-footer bg-white">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    @include('users.partials.create-modal')
    @include('users.partials.edit-modal')

    @push('scripts')
    <script>
        function openUserModal(id) {
            new bootstrap.Modal(document.getElementById(id)).show();
        }

        function closeUserModal(id) {
            const el = document.getElementById(id);
            (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).hide();
        }

        function openEditModal(id, name, username, email, role) {
            document.getElementById('edit_name').value     = name;
            document.getElementById('edit_username').value = username ?? '';
            document.getElementById('edit_email').value    = email;
            document.getElementById('edit_password').value = '';
            document.getElementById('edit_password_confirmation').value = '';
            document.getElementById('edit_role').value     = role ?? '';
            document.getElementById('edit_hidden_user_id').value = id;
            document.getElementById('edit_user_form').action = '/users/' + id;
            openUserModal('edit-user-modal');
        }
    </script>
    @endpush

@endsection
