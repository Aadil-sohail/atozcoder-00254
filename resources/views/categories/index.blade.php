@extends('layouts.header')

@section('title', 'Categories')

@section('header')
    <h2 class="text-lg font-semibold leading-tight text-gray-800">
        {{ __('Categories') }}
    </h2>
@endsection

@section('content')

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 p-2">
        <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
            <div>
                <p class="mb-0 text-muted small">{{ __('Manage the categories available across the system.') }}</p>
                <span class="text-muted" style="font-size:12px;">{{ $categories->total() }} {{ Str::plural('category', $categories->total()) }} total</span>
            </div>
            @can('create categories')
            <button type="button" onclick="openCreateModal()" class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                {{ __('Add Category') }}
            </button>
            @endcan
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:33%">#</th>
                        <th style="width:34%">Name</th>
                        <th style="width:33%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $index => $category)
                        <tr>
                            <td class="text-secondary">{{ $categories->firstItem() + $index }}</td>
                            <td class="fw-medium">{{ $category->name }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    @can('edit categories')
                                    <button type="button"
                                        onclick="openEditModal({{ $category->id }}, {{ json_encode($category->name) }})"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    @endcan

                                    @can('delete categories')
                                        @if ($category->status === '1')
                                            <form method="POST" action="{{ route('categories.destroy', $category) }}" class="d-inline"
                                                onsubmit="return confirmDelete(event, {{ json_encode(__('Disable this category? Its name will remain reserved.')) }});">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="fa-solid fa-tags fs-2 text-secondary opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-2">{{ __('No categories found.') }}</p>
                                @can('create categories')
                                    <button type="button" onclick="openCreateModal()" class="btn btn-sm btn-dark">
                                        {{ __('Add your first category') }}
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="card-footer bg-white">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    @include('categories.partials.create-modal')
    @include('categories.partials.edit-modal')

    @push('scripts')
    <script>
        const checkNameUrl = '{{ route('categories.check-name') }}';
        let nameCheckTimer = null;

        function checkCategoryName(inputId, feedbackId, excludeId) {
            const $input    = $('#' + inputId);
            const $feedback = $('#' + feedbackId);
            const name      = $input.val().trim();

            clearTimeout(nameCheckTimer);
            $feedback.html('');
            $input.removeClass('is-valid is-invalid');

            if (!name) return;

            nameCheckTimer = setTimeout(function () {
                $.post(checkNameUrl, {
                    _token:     '{{ csrf_token() }}',
                    name:       name,
                    exclude_id: excludeId || 0,
                }, function (res) {
                    if (res.taken) {
                        $input.addClass('is-invalid').removeClass('is-valid');
                        $feedback.html('<span class="text-danger"><i class="fa-solid fa-circle-xmark me-1"></i>This name is already taken.</span>');
                    } else {
                        $input.addClass('is-valid').removeClass('is-invalid');
                        $feedback.html('<span class="text-success"><i class="fa-solid fa-circle-check me-1"></i>Name is available.</span>');
                    }
                });
            }, 400);
        }

        $(function () {
            $('#create_name').on('keyup', function () {
                checkCategoryName('create_name', 'create_name_feedback', 0);
            });

            $('#edit_name').on('keyup', function () {
                const excludeId = $('#edit_category_id').val();
                checkCategoryName('edit_name', 'edit_name_feedback', excludeId);
            });
        });

        function openCreateModal() {
            $('#create_name').val('').removeClass('is-valid is-invalid');
            $('#create_name_feedback').html('');
            new bootstrap.Modal(document.getElementById('create-category-modal')).show();
        }

        function openEditModal(id, name) {
            document.getElementById('edit_category_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_category_form').action = '/categories/' + id;
            $('#edit_name').removeClass('is-valid is-invalid');
            $('#edit_name_feedback').html('');
            new bootstrap.Modal(document.getElementById('edit-category-modal')).show();
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            const instance = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
            instance.hide();
        }
    </script>
    @endpush

@endsection
