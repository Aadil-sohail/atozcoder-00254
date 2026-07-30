@extends('layouts.header')

@section('title', 'Sub Categories')

@section('header')
    <h2 class="text-lg font-semibold leading-tight text-gray-800">
        {{ __('Sub Categories') }}
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
                <p class="mb-0 text-muted small">{{ __('Manage the sub categories available under each main category.') }}</p>
                <span class="text-muted" style="font-size:12px;">{{ $subcategories->count() }} {{ Str::plural('sub category', $subcategories->count()) }} total</span>
            </div>
            @can('create subcategories')
            <button type="button" onclick="openCreateModal()" class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                {{ __('Add Sub Category') }}
            </button>
            @endcan
        </div>

        <div class="table-responsive">
            <table id="subcategories-table" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Sub Category') }}</th>
                        <th>{{ __('Main Category') }}</th>
                        <th>{{ __('Inserted By') }}</th>
                        <th class="no-sort">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subcategories as $subcategory)
                        <tr>
                            <td class="fw-medium">{{ $subcategory->name }}</td>
                            <td class="text-secondary">{{ $subcategory->category->name ?? '—' }}</td>
                            <td class="text-secondary">{{ $subcategory->inserted_by ?? '—' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    @can('edit subcategories')
                                    <button type="button"
                                        onclick="openEditModal({{ $subcategory->id }}, {{ json_encode($subcategory->name) }}, {{ $subcategory->category_id }})"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    @endcan

                                    @can('delete subcategories')
                                        @if ($subcategory->status === '1')
                                            <form method="POST" action="{{ route('subcategories.destroy', $subcategory) }}" class="d-inline"
                                                onsubmit="return confirmDelete(event, {{ json_encode(__('Disable this sub category? Its name will remain reserved.')) }});">
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
                                <i class="fa-solid fa-tag fs-2 text-secondary opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-2">{{ __('No sub categories found.') }}</p>
                                @can('create subcategories')
                                    <button type="button" onclick="openCreateModal()" class="btn btn-sm btn-dark">
                                        {{ __('Add your first sub category') }}
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('subcategories.partials.create-modal')
    @include('subcategories.partials.edit-modal')

    @push('scripts')
    <script>
        const checkNameUrl = '{{ route('subcategories.check-name') }}';
        let nameCheckTimer = null;

        /**
         * A sub category name only has to be unique inside its own main
         * category, so the check is skipped until a category is picked.
         */
        function checkSubcategoryName(inputId, categoryId, feedbackId, excludeId) {
            const $input    = $('#' + inputId);
            const $category = $('#' + categoryId);
            const $feedback = $('#' + feedbackId);
            const name      = $input.val().trim();

            clearTimeout(nameCheckTimer);
            $feedback.html('');
            $input.removeClass('is-valid is-invalid');

            if (!name || !$category.val()) return;

            nameCheckTimer = setTimeout(function () {
                $.post(checkNameUrl, {
                    _token:      '{{ csrf_token() }}',
                    name:        name,
                    category_id: $category.val(),
                    exclude_id:  excludeId || 0,
                }, function (res) {
                    if (res.taken) {
                        $input.addClass('is-invalid').removeClass('is-valid');
                        $feedback.html('<span class="text-danger"><i class="fa-solid fa-circle-xmark me-1"></i>This name is already taken in the selected category.</span>');
                    } else {
                        $input.addClass('is-valid').removeClass('is-invalid');
                        $feedback.html('<span class="text-success"><i class="fa-solid fa-circle-check me-1"></i>Name is available.</span>');
                    }
                });
            }, 400);
        }

        $(function () {
            @if ($subcategories->isNotEmpty())
            $('#subcategories-table').DataTable({
                columnDefs: [
                    { orderable: false, targets: 'no-sort' },
                ],
                order: [],
            });
            @endif

            $('#create_name, #create_category_id').on('keyup change', function () {
                checkSubcategoryName('create_name', 'create_category_id', 'create_name_feedback', 0);
            });

            $('#edit_name, #edit_category_id').on('keyup change', function () {
                const excludeId = $('#edit_subcategory_id').val();
                checkSubcategoryName('edit_name', 'edit_category_id', 'edit_name_feedback', excludeId);
            });
        });

        function openCreateModal() {
            $('#create_name').val('').removeClass('is-valid is-invalid');
            $('#create_category_id').val('');
            $('#create_name_feedback').html('');
            new bootstrap.Modal(document.getElementById('create-subcategory-modal')).show();
        }

        function openEditModal(id, name, categoryId) {
            document.getElementById('edit_subcategory_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_category_id').value = categoryId;
            document.getElementById('edit_subcategory_form').action = '/subcategories/' + id;
            $('#edit_name').removeClass('is-valid is-invalid');
            $('#edit_name_feedback').html('');
            new bootstrap.Modal(document.getElementById('edit-subcategory-modal')).show();
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            const instance = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
            instance.hide();
        }
    </script>
    @endpush

@endsection
