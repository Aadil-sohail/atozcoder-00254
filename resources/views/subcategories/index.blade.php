@extends('layouts.header')

@section('title', 'Sub Categories')

@section('header')
    <h2 class="text-lg font-semibold leading-tight text-gray-800">
        {{ __('Sub Categories') }}
    </h2>
@endsection

@push('styles')
    <style>
        /* ── Sub categories grid ──────────────────────────────────────
           Bootstrap paints table cells through --bs-table-bg (and the
           --bs-table-bg-state override on hover), so backgrounds are set
           through those variables rather than background-color, which the
           inset shadow would otherwise sit on top of.
           ------------------------------------------------------------ */
        #subcategories-table {
            border-collapse: separate;
            border-spacing: 0;
        }

        #subcategories-table thead th {
            --bs-table-bg: #1f2937;
            /* DataTables draws the sort arrows as CSS triangles in near-black,
               which would vanish on a dark header — recoloured through its own
               variables rather than by restyling the pseudo elements. */
            --dt-order-arrow_color: #fff;
            --dt-order-arrow_color-current: #fff;
            --dt-order-arrow_opacity: .4;
            --dt-order-arrow_opacity-current: 1;
            color: #fff;
            font-weight: 600;
            font-size: 13px;
            padding: 15px 18px;
            border-bottom: none;
            white-space: nowrap;
        }

        #subcategories-table thead th:first-child { border-top-left-radius: 8px; }
        #subcategories-table thead th:last-child  { border-top-right-radius: 8px; }

        #subcategories-table tbody td {
            padding: 14px 18px;
            border-bottom: 1px solid #eef1f5;
            font-size: 14px;
            color: #374151;
        }

        #subcategories-table tbody tr:not(.subcategory-group):hover > td {
            --bs-table-bg-state: #f7fafc;
        }

        /* Main category heading row injected by DataTables' drawCallback.
           Clicking it collapses the sub categories filed under it. */
        #subcategories-table tbody tr.subcategory-group > td {
            --bs-table-bg: #dfe8f3;
            border-top: 1px solid #cddbeb;
            border-bottom: 1px solid #cddbeb;
            color: #1f4e79;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: .01em;
            padding: 11px 18px;
            text-align: center;
            cursor: pointer;
            user-select: none;
        }

        #subcategories-table tbody tr.subcategory-group:hover > td {
            --bs-table-bg-state: #d3e0f0;
        }

        /* Turns to point right while the group is collapsed */
        #subcategories-table tbody tr.subcategory-group .group-chevron {
            transition: transform .15s ease;
            font-size: 12px;
        }

        #subcategories-table tbody tr.subcategory-group.collapsed .group-chevron {
            transform: rotate(-90deg);
        }

        #subcategories-table tbody tr.subcategory-group .group-count {
            color: #6b8bb5;
            font-weight: 500;
        }

        /* Last row sits flush against the card, without a trailing divider */
        #subcategories-table tbody tr:last-child > td { border-bottom: none; }

        /* Toolbar: length menu, category filter and search on one line */
        #subcategories-table_wrapper .dt-layout-row:first-child,
        .dt-container .dt-layout-row:first-child {
            align-items: center;
            row-gap: .5rem;
        }

        #category-filter-wrap label { white-space: nowrap; }
    </style>
@endpush

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
                <p class="mb-0 text-muted small">{{ __('Manage the sub categories available under each main category.') }}
                </p>
                <span class="text-muted" style="font-size:12px;">{{ $subcategories->count() }}
                    {{ Str::plural('sub category', $subcategories->count()) }} total</span>
            </div>
            @can('create subcategories')
                <button type="button" onclick="openCreateModal()" class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    {{ __('Add Sub Category') }}
                </button>
            @endcan
        </div>

        {{-- Narrows the table to a single main category; the group headings
             still print, so one category shows on its own with its heading.
             Moved into the DataTables toolbar once the table is built (see
             the script below), and hidden altogether when there is no table. --}}
        @if ($subcategories->isNotEmpty())
        <div id="category-filter-wrap" class="d-flex align-items-center gap-2 px-3 pt-3">
            <label for="category-filter" class="form-label small text-muted mb-0">{{ __('Filter by main category') }}</label>
            <select id="category-filter" class="form-select form-select-sm" style="max-width:220px;">
                <option value="">{{ __('All main categories') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="table-responsive">
            <table id="subcategories-table" class="table table-hover align-middle mb-0">
                <thead>
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
                            <td class="fw-medium">
                                <i class="text-secondary opacity-75 me-2"></i>{{ $subcategory->name }}
                            </td>
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
                                            <form method="POST" action="{{ route('subcategories.destroy', $subcategory) }}"
                                                class="d-inline"
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
                const $input = $('#' + inputId);
                const $category = $('#' + categoryId);
                const $feedback = $('#' + feedbackId);
                const name = $input.val().trim();

                clearTimeout(nameCheckTimer);
                $feedback.html('');
                $input.removeClass('is-valid is-invalid');

                if (!name || !$category.val()) return;

                nameCheckTimer = setTimeout(function () {
                    $.post(checkNameUrl, {
                        _token: '{{ csrf_token() }}',
                        name: name,
                        category_id: $category.val(),
                        exclude_id: excludeId || 0,
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
                    // The main category column is hidden and printed as a heading row
                    // above each group instead of being repeated on every row.
                    const categoryColumn = 1;

                    // Main categories the user has clicked shut. Kept across
                    // draws so sorting, searching or paging doesn't reopen them.
                    const collapsedGroups = new Set();

                    const subcategoriesTable = $('#subcategories-table').DataTable({
                        columnDefs: [
                            { orderable: false, targets: 'no-sort' },
                            { visible: false, targets: categoryColumn },
                        ],
                        // Fixed pre-sort keeps the groups together no matter which
                        // column the user sorts on inside them.
                        orderFixed: [[categoryColumn, 'asc']],
                        order: [[0, 'asc']],
                        drawCallback: function () {
                            const api = this.api();
                            const rows = api.rows({ page: 'current' }).nodes();
                            const groups = api.column(categoryColumn, { page: 'current' }).data();
                            let last = null;

                            // How many sub categories each heading covers on this page.
                            const counts = {};
                            groups.each(function (group) {
                                counts[group] = (counts[group] || 0) + 1;
                            });

                            groups.each(function (group, i) {
                                const collapsed = collapsedGroups.has(group);

                                if (last !== group) {
                                    const count = counts[group];
                                    const $heading = $(
                                        '<tr class="subcategory-group' + (collapsed ? ' collapsed' : '') + '">' +
                                        '<td colspan="3">' +
                                        '<i class="fa-solid fa-chevron-down group-chevron me-2"></i>' +
                                        '<i class="fa-solid fa-folder me-2"></i>' +
                                        '<span class="group-name"></span>' +
                                        ' <span class="group-count">(' + count + ')</span>' +
                                        '</td></tr>'
                                    );

                                    // Set as text, and stored on the element rather than in an
                                    // attribute, so a name is never re-parsed as markup.
                                    $heading.find('.group-name').text($('<div>').html(group).text());
                                    $heading.data('group', group);

                                    $(rows).eq(i).before($heading);

                                    last = group;
                                }

                                // Row nodes are reused between draws, so the hidden state is
                                // set either way rather than only when it needs hiding.
                                $(rows).eq(i).toggleClass('d-none', collapsed);
                            });
                        },
                    });

                    // Sits beside "entries per page" rather than on a row of its own.
                    // If DataTables' toolbar markup isn't where we expect it, the
                    // filter simply stays above the table where it was rendered.
                    const $toolbar = $('#subcategories-table').closest('.dt-container').find('.dt-layout-start').first();

                    if ($toolbar.length) {
                        $('#category-filter-wrap').removeClass('px-3 pt-3').addClass('ms-3').appendTo($toolbar);
                        $toolbar.addClass('d-flex align-items-center flex-wrap');
                    }

                    // Clicking a heading folds its sub categories away. draw(false)
                    // redraws in place instead of jumping back to the first page.
                    $('#subcategories-table tbody').on('click', 'tr.subcategory-group', function () {
                        const group = $(this).data('group');

                        if (collapsedGroups.has(group)) {
                            collapsedGroups.delete(group);
                        } else {
                            collapsedGroups.add(group);
                        }

                        subcategoriesTable.draw(false);
                    });

                    // Exact match against the hidden main category column, so
                    // picking "abc2" does not also pull in an "abc20".
                    $('#category-filter').on('change', function () {
                        const name = this.value;

                        subcategoriesTable
                            .column(categoryColumn)
                            .search(name ? '^' + $.fn.dataTable.util.escapeRegex(name) + '$' : '', true, false)
                            .draw();
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