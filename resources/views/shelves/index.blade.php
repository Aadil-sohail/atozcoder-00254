@extends('layouts.header')

@section('title', 'Shelves')

@section('header')
    <h2 class="text-lg font-semibold leading-tight text-gray-800">
        {{ __('Shelves') }}
    </h2>
@endsection

@push('styles')
    <style>
        /* ── Shelves grid ──────────────────────────────────────
           Bootstrap paints table cells through --bs-table-bg (and the
           --bs-table-bg-state override on hover), so backgrounds are set
           through those variables rather than background-color, which the
           inset shadow would otherwise sit on top of.
           ------------------------------------------------------------ */
        #shelves-table {
            border-collapse: separate;
            border-spacing: 0;
        }

        #shelves-table thead th {
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

        #shelves-table thead th:first-child { border-top-left-radius: 8px; }
        #shelves-table thead th:last-child  { border-top-right-radius: 8px; }

        #shelves-table tbody td {
            padding: 14px 18px;
            border-bottom: 1px solid #eef1f5;
            font-size: 14px;
            color: #374151;
        }

        #shelves-table tbody tr:not(.shelf-group):hover > td {
            --bs-table-bg-state: #f7fafc;
        }

        /* Main rack heading row injected by DataTables' drawCallback.
           Clicking it collapses the shelves filed under it. */
        #shelves-table tbody tr.shelf-group > td {
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

        #shelves-table tbody tr.shelf-group:hover > td {
            --bs-table-bg-state: #d3e0f0;
        }

        /* Turns to point right while the group is collapsed */
        #shelves-table tbody tr.shelf-group .group-chevron {
            transition: transform .15s ease;
            font-size: 12px;
        }

        #shelves-table tbody tr.shelf-group.collapsed .group-chevron {
            transform: rotate(-90deg);
        }

        #shelves-table tbody tr.shelf-group .group-count {
            color: #6b8bb5;
            font-weight: 500;
        }

        /* Last row sits flush against the card, without a trailing divider */
        #shelves-table tbody tr:last-child > td { border-bottom: none; }

        /* Toolbar: length menu, rack filter and search on one line */
        #shelves-table_wrapper .dt-layout-row:first-child,
        .dt-container .dt-layout-row:first-child {
            align-items: center;
            row-gap: .5rem;
        }

        #rack-filter-wrap label { white-space: nowrap; }

        /* Rename / delete on a rack heading, pinned right so the name stays centred. */
        #shelves-table tbody tr.shelf-group > td { position: relative; }

        #shelves-table .group-actions {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 6px;
        }

        #shelves-table .group-actions .btn,
        #empty-racks .btn {
            --bs-btn-padding-y: .15rem;
            --bs-btn-padding-x: .45rem;
            --bs-btn-font-size: .75rem;
            background: #fff;
        }

        /* ── Drag and drop ─────────────────────────────────────────── */
        #shelves-table .drag-handle {
            cursor: grab;
            color: #9ca3af;
            padding: 4px 6px;
            margin-left: -6px;
        }

        #shelves-table .drag-handle:hover { color: #374151; }

        /* Searching shows part of a rack only, so dragging pauses. */
        #shelves-table.drag-off .drag-handle {
            cursor: not-allowed;
            opacity: .35;
        }

        /* The gap the row will drop into. */
        #shelves-table tbody tr.sortable-ghost > td {
            --bs-table-bg: #e8f0fb;
            opacity: .6;
        }

        #shelves-table tbody tr.sortable-chosen { cursor: grabbing; }

        /* ── Rack view: each rack drawn as a shelving unit ─────────── */
        .rack-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 28px;
            padding: 20px 16px 8px;
        }

        .rack-unit { display: flex; flex-direction: column; }

        .rack-top {
            display: flex; align-items: center; justify-content: space-between; gap: 8px;
            background: #1f2937; color: #fff; border-radius: 8px 8px 0 0;
            padding: 9px 12px; font-size: 14px;
        }
        .rack-top-count { font-size: 12px; color: #9ca3af; margin-left: 6px; }

        .rack-btn {
            border: 1px solid #d1d5db; background: #fff; color: #374151; border-radius: 5px;
            width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;
            font-size: 11px; padding: 0;
        }
        .rack-top .rack-btn { border-color: #4b5563; background: #374151; color: #e5e7eb; }
        .rack-btn:hover { filter: brightness(.95); }
        .rack-top .rack-btn.rack-delete:hover { background: #dc2626; border-color: #dc2626; color: #fff; }
        .rack-btn-sm { width: 22px; height: 22px; font-size: 10px; }
        .rack-btn-danger { color: #dc3545; }
        .rack-btn-danger:hover { background: #dc3545; border-color: #dc3545; color: #fff; }

        /* The uprights are the frame's side borders. */
        /* Shows 4 shelves; any more and the rack scrolls up and down. */
        .rack-frame {
            border-left: 10px solid #6b7280; border-right: 10px solid #6b7280;
            background: #f8fafc;
            min-height: 120px;
            max-height: calc(4 * 60px);
            overflow-y: auto;
        }

        /* Each shelf stands on its plank (the bottom border). Same height for
           every shelf, so the rack always fits exactly 4 before scrolling. */
        .rack-shelf {
            height: 60px;
            padding: 0 10px;
            border-bottom: 7px solid #94a3b8;
            display: flex; align-items: center;
        }
        .rack-shelf.sortable-ghost { background: #e8f0fb; opacity: .7; }

        .rack-shelf-label { display: flex; align-items: center; gap: 6px; flex: 1; min-width: 0; }
        .rack-grip { cursor: grab; color: #9ca3af; font-size: 12px; }
        .rack-grip:hover { color: #374151; }
        .rack-shelf-name { font-size: 13px; font-weight: 600; color: #1f2937; text-decoration: none; }
        .rack-shelf-name:hover { color: #0d6efd; }
        .rack-shelf-count {
            font-size: 11px; background: #e5e7eb; color: #374151; border-radius: 10px; padding: 1px 7px; flex-shrink: 0;
        }

        .rack-shelf-count:hover { background: #dbeafe; color: #1e40af; }

        .rack-shelf-none {
            height: auto;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 28px 10px; color: #9ca3af; font-size: 13px; border-bottom: none; box-shadow: none;
        }

        /* Feet under the uprights. */
        .rack-feet { display: flex; justify-content: space-between; }
        .rack-feet span { width: 16px; height: 10px; background: #4b5563; border-radius: 0 0 3px 3px; }

        #shelf-view-tabs .nav-link { color: #4b5563; font-size: 14px; }
        #shelf-view-tabs .nav-link.active { color: #111827; font-weight: 600; }
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
                <p class="mb-0 text-muted small">{{ __('Manage the shelves available under each rack.') }}
                </p>
                <span class="text-muted" style="font-size:12px;">{{ $shelves->count() }}
                    {{ Str::plural('shelf', $shelves->count()) }} total</span>
            </div>
            <div class="d-flex gap-2">
                @can('create racks')
                    <button type="button" onclick="openCreateRackModal()" class="btn btn-outline-dark btn-sm d-flex align-items-center gap-2">
                        <i class="fa-solid fa-warehouse"></i>
                        {{ __('Add Rack') }}
                    </button>
                @endcan
                @can('create shelves')
                    <button type="button" onclick="openCreateModal()" class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                        <i class="fa-solid fa-plus"></i>
                        {{ __('Add Shelf') }}
                    </button>
                @endcan
            </div>
        </div>

        {{-- Two ways to look at the same racks: drawn as shelving units, or as the
             sortable list. The choice is remembered (see the script below). --}}
        <ul class="nav nav-tabs px-3 pt-3" id="shelf-view-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="rack-view-tab" data-bs-toggle="tab" data-bs-target="#rack-view" type="button" role="tab">
                    <i class="fa-solid fa-warehouse me-1"></i>{{ __('Rack view') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="list-view-tab" data-bs-toggle="tab" data-bs-target="#list-view" type="button" role="tab">
                    <i class="fa-solid fa-list me-1"></i>{{ __('List') }}
                </button>
            </li>
        </ul>

        <div class="tab-content">
        <div class="tab-pane fade show active" id="rack-view" role="tabpanel">
            @include('shelves.partials.rack-view')
        </div>

        <div class="tab-pane fade" id="list-view" role="tabpanel">
        {{-- Racks with no shelves have no heading in the table, so they are
             listed here, to be renamed, deleted or given a first shelf. --}}
        @if ($emptyRacks->isNotEmpty())
            <div id="empty-racks" class="d-flex align-items-center flex-wrap gap-2 px-3 pt-3">
                <span class="small text-muted me-1">{{ __('Racks with no shelves yet:') }}</span>
                @foreach ($emptyRacks as $rack)
                    <span class="d-inline-flex align-items-center gap-1 border rounded px-2 py-1 bg-light">
                        <i class="fa-solid fa-warehouse text-secondary small"></i>
                        <span class="small fw-medium">{{ $rack->name }}</span>
                        @can('create shelves')
                            <button type="button" class="btn btn-outline-dark ms-1" title="{{ __('Add a shelf to this rack') }}"
                                onclick="openCreateModal({{ $rack->id }})"><i class="fa-solid fa-plus"></i></button>
                        @endcan
                        @can('edit racks')
                            <button type="button" class="btn btn-outline-primary rack-rename" title="{{ __('Rename rack') }}"
                                data-id="{{ $rack->id }}" data-name="{{ $rack->name }}"><i class="fa-solid fa-pen"></i></button>
                        @endcan
                        @can('delete racks')
                            <button type="button" class="btn btn-outline-danger rack-delete" title="{{ __('Delete rack') }}"
                                data-id="{{ $rack->id }}" data-name="{{ $rack->name }}" data-shelves="0"><i class="fa-solid fa-trash"></i></button>
                        @endcan
                    </span>
                @endforeach
            </div>
        @endif

        {{-- Narrows the table to a single rack; the group headings
             still print, so one rack shows on its own with its heading.
             Moved into the DataTables toolbar once the table is built (see
             the script below), and hidden altogether when there is no table. --}}
        @if ($shelves->isNotEmpty())
        <div id="rack-filter-wrap" class="d-flex align-items-center gap-2 px-3 pt-3">
            <label for="rack-filter" class="form-label small text-muted mb-0">{{ __('Filter by rack') }}</label>
            <select id="rack-filter" class="form-select form-select-sm" style="max-width:220px;">
                <option value="">{{ __('All racks') }}</option>
                @foreach ($racks as $rack)
                    <option value="{{ $rack->name }}" data-id="{{ $rack->id }}" @selected($selectedRack?->id === $rack->id)>{{ $rack->name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="table-responsive">
            <table id="shelves-table" class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        {{-- The three visible columns share the width equally. --}}
                        <th style="width:33.33%">{{ __('Shelf') }}</th>
                        <th>{{ __('Rack') }}</th>
                        <th>{{ __('Order') }}</th>
                        <th style="width:33.33%">{{ __('Products') }}</th>
                        <th class="no-sort" style="width:33.33%">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($shelves as $shelf)
                        <tr data-id="{{ $shelf->id }}" data-rack-id="{{ $shelf->rack_id }}">
                            <td class="fw-medium">
                                @can('edit shelves')
                                    <i class="fa-solid fa-grip-vertical drag-handle me-2" title="{{ __('Drag to reorder') }}"></i>
                                @endcan
                                {{ $shelf->name }}
                            </td>
                            <td class="text-secondary">{{ $shelf->rack->name ?? '—' }}</td>
                            <td>{{ $shelf->sort_order }}</td>
                            <td>
                                <a href="{{ route('shelves.show', $shelf) }}" class="text-decoration-none">
                                    {{ $shelf->products_count }} {{ Str::plural('product', $shelf->products_count) }}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('shelves.show', $shelf) }}" class="btn btn-sm btn-outline-secondary"
                                        title="{{ __('View the products on this shelf') }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    @can('edit shelves')
                                        <button type="button"
                                            onclick="openEditModal({{ $shelf->id }}, {{ json_encode($shelf->name) }}, {{ $shelf->rack_id }})"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                    @endcan

                                    @can('delete shelves')
                                        @if ($shelf->status === '1')
                                            <form method="POST" action="{{ route('shelves.destroy', $shelf) }}"
                                                class="d-inline"
                                                onsubmit="return confirmDelete(event, {{ json_encode(__('Disable this shelf? Its name will remain reserved.')) }});">
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
                            <td colspan="5" class="text-center py-5">
                                <i class="fa-solid fa-layer-group fs-2 text-secondary opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-2">{{ __('No shelves found.') }}</p>
                                @can('create shelves')
                                    <button type="button" onclick="openCreateModal()" class="btn btn-sm btn-dark">
                                        {{ __('Add your first shelf') }}
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>{{-- #list-view --}}
        </div>{{-- .tab-content --}}
    </div>

    @include('shelves.partials.create-modal')
    @include('shelves.partials.edit-modal')
    @can('create racks')
        @include('shelves.partials.create-rack-modal')
    @endcan
    @can('edit racks')
        @include('shelves.partials.edit-rack-modal')
    @endcan
    @can('delete racks')
        {{-- Sent by the trash button on a rack, after the confirm. --}}
        <form id="rack-delete-form" method="POST" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endcan

    {{-- Built here because @json cannot parse a multi-line argument. --}}
    @php($rackInfo = $racks->mapWithKeys(fn ($rack) => [$rack->name => ['id' => $rack->id, 'name' => $rack->name, 'shelves' => $shelves->where('rack_id', $rack->id)->count()]]))

    @push('scripts')
        @can('edit shelves')
            <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
        @endcan
        <script>
            const checkNameUrl = '{{ route('shelves.check-name') }}';

            // Every rack in use, by name (headings only know the name): its id and
            // how many shelves it has, for the rename / delete buttons.
            const rackInfo = @json($rackInfo);

            /**
             * Rename and delete buttons on a rack heading, for those allowed to.
             */
            function addRackActions($cell, rack) {
                if (!rack) return;

                const $actions = $('<span class="group-actions"></span>');

                @can('edit racks')
                    $('<button type="button" class="btn btn-outline-primary rack-rename" title="Rename rack"><i class="fa-solid fa-pen"></i></button>')
                        .attr({ 'data-id': rack.id, 'data-name': rack.name })
                        .appendTo($actions);
                @endcan
                @can('delete racks')
                    $('<button type="button" class="btn btn-outline-danger rack-delete" title="Delete rack"><i class="fa-solid fa-trash"></i></button>')
                        .attr({ 'data-id': rack.id, 'data-name': rack.name, 'data-shelves': rack.shelves })
                        .appendTo($actions);
                @endcan

                if ($actions.children().length) $cell.append($actions);
            }

            $(document).on('click', '.rack-rename', function (e) {
                e.stopPropagation();
                openRenameRackModal(this.dataset.id, this.dataset.name);
            });

            $(document).on('click', '.rack-delete', function (e) {
                e.stopPropagation();

                const name = this.dataset.name;
                const shelves = Number(this.dataset.shelves || 0);
                const action = '{{ url('racks') }}/' + this.dataset.id;

                Swal.fire({
                    title: 'Delete rack "' + name + '"?',
                    text: shelves
                        ? 'Its ' + shelves + (shelves === 1 ? ' shelf' : ' shelves') + ' will be deleted with it. Not possible while any of them holds products.'
                        : 'The rack has no shelves.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                }).then(function (result) {
                    if (!result.isConfirmed) return;

                    const form = document.getElementById('rack-delete-form');
                    form.action = action;
                    form.submit();
                });
            });

            function openRenameRackModal(id, name) {
                document.getElementById('edit_rack_id').value = id;
                document.getElementById('edit_rack_form').action = '{{ url('racks') }}/' + id;
                $('#rack_edit_name').val(name).removeClass('is-valid is-invalid');
                $('#rack_edit_name_feedback').html('');
                new bootstrap.Modal(document.getElementById('edit-rack-modal')).show();
            }

            // The same live check as Add Rack, skipping the rack being renamed.
            let rackRenameTimer = null;

            $(function () {
                $('#rack_edit_name').on('keyup', function () {
                    const $input = $(this);
                    const $feedback = $('#rack_edit_name_feedback');
                    const name = $input.val().trim();

                    clearTimeout(rackRenameTimer);
                    $feedback.html('');
                    $input.removeClass('is-valid is-invalid');

                    if (!name) return;

                    rackRenameTimer = setTimeout(function () {
                        $.post('{{ route('racks.check-name') }}', {
                            _token: '{{ csrf_token() }}',
                            name: name,
                            exclude_id: $('#edit_rack_id').val(),
                        }, function (res) {
                            $input.toggleClass('is-invalid', res.taken).toggleClass('is-valid', !res.taken);
                            $feedback.html(res.taken
                                ? '<span class="text-danger"><i class="fa-solid fa-circle-xmark me-1"></i>A rack with this name already exists.</span>'
                                : '<span class="text-success"><i class="fa-solid fa-circle-check me-1"></i>Name is available.</span>');
                        });
                    }, 400);
                });
            });
            let nameCheckTimer = null;

            /**
             * A shelf name only has to be unique inside its own main
             * rack, so the check is skipped until a rack is picked.
             */
            function checkShelfName(inputId, rackId, feedbackId, excludeId) {
                const $input = $('#' + inputId);
                const $rack = $('#' + rackId);
                const $feedback = $('#' + feedbackId);
                const name = $input.val().trim();

                clearTimeout(nameCheckTimer);
                $feedback.html('');
                $input.removeClass('is-valid is-invalid');

                if (!name || !$rack.val()) return;

                nameCheckTimer = setTimeout(function () {
                    $.post(checkNameUrl, {
                        _token: '{{ csrf_token() }}',
                        name: name,
                        rack_id: $rack.val(),
                        exclude_id: excludeId || 0,
                    }, function (res) {
                        if (res.taken) {
                            $input.addClass('is-invalid').removeClass('is-valid');
                            $feedback.html('<span class="text-danger"><i class="fa-solid fa-circle-xmark me-1"></i>This name is already taken in the selected rack.</span>');
                        } else {
                            $input.addClass('is-valid').removeClass('is-invalid');
                            $feedback.html('<span class="text-success"><i class="fa-solid fa-circle-check me-1"></i>Name is available.</span>');
                        }
                    });
                }, 400);
            }

            $(function () {
                @if ($shelves->isNotEmpty())
                    // The rack column is hidden and printed as a heading row
                    // above each group instead of being repeated on every row.
                    const rackColumn = 1;

                    // Hidden too: each shelf's position in its rack, which is what
                    // the rows are ordered by inside a group.
                    const orderColumn = 2;

                    // Main racks the user has clicked shut. Kept across
                    // draws so sorting, searching or paging doesn't reopen them.
                    const collapsedGroups = new Set();

                    const shelvesTable = $('#shelves-table').DataTable({
                        columnDefs: [
                            { visible: false, targets: [rackColumn, orderColumn] },
                            { type: 'num', targets: orderColumn },
                            // The order is the one set by dragging, so the column
                            // headers don't re-sort.
                            { orderable: false, targets: '_all' },
                        ],
                        orderFixed: [[rackColumn, 'asc'], [orderColumn, 'asc']],
                        // Keep the widths set on the headers instead of sizing to content.
                        autoWidth: false,
                        order: [],
                        drawCallback: function () {
                            const api = this.api();
                            const rows = api.rows({ page: 'current' }).nodes();
                            const groups = api.column(rackColumn, { page: 'current' }).data();
                            let last = null;

                            // How many shelves each heading covers on this page.
                            const counts = {};
                            groups.each(function (group) {
                                counts[group] = (counts[group] || 0) + 1;
                            });

                            groups.each(function (group, i) {
                                const collapsed = collapsedGroups.has(group);

                                if (last !== group) {
                                    const count = counts[group];
                                    const $heading = $(
                                        '<tr class="shelf-group' + (collapsed ? ' collapsed' : '') + '">' +
                                        '<td colspan="3">' +
                                        '<i class="fa-solid fa-chevron-down group-chevron me-2"></i>' +
                                        '<i class="fa-solid fa-warehouse me-2"></i>' +
                                        '<span class="group-name"></span>' +
                                        ' <span class="group-count">(' + count + ')</span>' +
                                        '</td></tr>'
                                    );

                                    // Set as text, and stored on the element rather than in an
                                    // attribute, so a name is never re-parsed as markup.
                                    $heading.find('.group-name').text($('<div>').html(group).text());
                                    $heading.data('group', group);
                                    addRackActions($heading.children('td'), rackInfo[$('<div>').html(group).text()]);

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
                    const $toolbar = $('#shelves-table').closest('.dt-container').find('.dt-layout-start').first();

                    if ($toolbar.length) {
                        $('#rack-filter-wrap').removeClass('px-3 pt-3').addClass('ms-3').appendTo($toolbar);
                        $toolbar.addClass('d-flex align-items-center flex-wrap');
                    }

                    // Clicking a heading folds its shelves away. draw(false)
                    // redraws in place instead of jumping back to the first page.
                    $('#shelves-table tbody').on('click', 'tr.shelf-group', function (e) {
                        // Rename / delete are buttons on the heading, not a fold.
                        if ($(e.target).closest('.group-actions').length) return;

                        const group = $(this).data('group');

                        if (collapsedGroups.has(group)) {
                            collapsedGroups.delete(group);
                        } else {
                            collapsedGroups.add(group);
                        }

                        shelvesTable.draw(false);
                    });

                    // Exact match against the hidden rack column, so
                    // picking "abc2" does not also pull in an "abc20".
                    $('#rack-filter').on('change', function () {
                        const name = this.value;

                        shelvesTable
                            .column(rackColumn)
                            .search(name ? '^' + $.fn.dataTable.util.escapeRegex(name) + '$' : '', true, false)
                            .draw();
                    });

                    // Opened from a rack's "shelves" link: start on that rack.
                    if ($('#rack-filter').val()) {
                        $('#rack-filter').trigger('change');
                    }

                    @can('edit shelves')
                        initShelfDragAndDrop(shelvesTable, rackColumn, orderColumn);
                    @endcan

                    // After a drag in the rack view: give the list the same order.
                    window.arrangeListView = function (ids) {
                        ids.forEach(function (id, position) {
                            const node = shelvesTable.row('tr[data-id="' + id + '"]').node();
                            if (node) shelvesTable.cell(node, orderColumn).data(position + 1);
                        });
                        shelvesTable.draw(false);
                    };
                @endif

                $('#create_name, #create_rack_id').on('keyup change', function () {
                    checkShelfName('create_name', 'create_rack_id', 'create_name_feedback', 0);
                });

                $('#edit_name, #edit_rack_id').on('keyup change', function () {
                    const excludeId = $('#edit_shelf_id').val();
                    checkShelfName('edit_name', 'edit_rack_id', 'edit_name_feedback', excludeId);
                });
            });

            /**
             * Drag a shelf by its grip to change its place in the rack. The new
             * order is saved straight away; the table is only re-sorted once the
             * server has it, and put back as it was if saving fails.
             */
            function initShelfDragAndDrop(table, rackColumn, orderColumn) {
                const $table = $('#shelves-table');

                const rackOf = (node) => table.row(node).data()[rackColumn];

                const sortable = Sortable.create($table.find('tbody')[0], {
                    handle: '.drag-handle',
                    // Shelves move; the rack headings between them don't.
                    draggable: 'tr[data-id]',
                    animation: 150,

                    onEnd: function (evt) {
                        if (evt.oldIndex === evt.newIndex) return;

                        const row = evt.item;
                        const rack = rackOf(row);
                        const $heading = $(row).prevAll('tr.shelf-group').first();

                        // Dropped under another rack's heading: undo. Moving a shelf
                        // to another rack is done from its Edit button.
                        if ($heading.data('group') !== rack) {
                            table.draw(false);
                            Toast.fire({ icon: 'warning', title: 'A shelf can only be moved within its own rack. Use Edit to move it to another rack.' });
                            return;
                        }

                        // The rack as it now reads on screen...
                        const shown = $heading.nextUntil('tr.shelf-group').filter('tr[data-id]')
                            .map((i, el) => Number(el.dataset.id)).get();

                        // ...spliced into the whole rack, which can run over more
                        // than one page: the page shows one unbroken stretch of it.
                        const rackRows = table.rows({ order: 'applied' }).nodes().toArray()
                            .filter((node) => rackOf(node) === rack);
                        const ids = rackRows.map((node) => Number(node.dataset.id));
                        const start = ids.findIndex((id) => shown.includes(id));
                        ids.splice(start, shown.length, ...shown);

                        sortable.option('disabled', true);

                        $.post('{{ route('shelves.reorder') }}', {
                            _token: '{{ csrf_token() }}',
                            rack_id: row.dataset.rackId,
                            ids: ids,
                        })
                            .done(function (res) {
                                ids.forEach(function (id, position) {
                                    const node = rackRows.find((n) => Number(n.dataset.id) === id);
                                    table.cell(node, orderColumn).data(position + 1);
                                });

                                table.draw(false);
                                arrangeRackView(ids);
                                Toast.fire({ icon: 'success', title: res.message });
                            })
                            .fail(function (xhr) {
                                table.draw(false);
                                Toast.fire({
                                    icon: 'error',
                                    title: (xhr.responseJSON && xhr.responseJSON.message) || 'Could not save the new order.',
                                });
                            })
                            .always(syncDragState);
                    },
                });

                // A search can leave gaps in a rack, and then there is no telling
                // where a dropped shelf belongs among the hidden ones.
                function syncDragState() {
                    const off = table.search() !== '';

                    sortable.option('disabled', off);
                    $table.toggleClass('drag-off', off);
                    $table.find('.drag-handle').attr('title', off ? 'Clear the search to reorder' : 'Drag to reorder');
                }

                table.on('search.dt', syncDragState);
                syncDragState();
            }

            @can('create racks')
                function openCreateRackModal() {
                    $('#rack_create_name').val('').removeClass('is-valid is-invalid');
                    $('#rack_create_name_feedback').html('');
                    new bootstrap.Modal(document.getElementById('create-rack-modal')).show();
                }

                // Rack names are unique, the same live check as on the Racks page.
                let rackNameTimer = null;

                $(function () {
                    $('#rack_create_name').on('keyup', function () {
                        const $input = $(this);
                        const $feedback = $('#rack_create_name_feedback');
                        const name = $input.val().trim();

                        clearTimeout(rackNameTimer);
                        $feedback.html('');
                        $input.removeClass('is-valid is-invalid');

                        if (!name) return;

                        rackNameTimer = setTimeout(function () {
                            $.post('{{ route('racks.check-name') }}', { _token: '{{ csrf_token() }}', name: name }, function (res) {
                                $input.toggleClass('is-invalid', res.taken).toggleClass('is-valid', !res.taken);
                                $feedback.html(res.taken
                                    ? '<span class="text-danger"><i class="fa-solid fa-circle-xmark me-1"></i>A rack with this name already exists.</span>'
                                    : '<span class="text-success"><i class="fa-solid fa-circle-check me-1"></i>Name is available.</span>');
                            });
                        }, 400);
                    });
                });
            @endcan

            function openCreateModal(rackId) {
                $('#create_name').val('').removeClass('is-valid is-invalid');
                // Adding shelves usually happens one rack at a time, so the rack
                // being looked at is picked already.
                $('#create_rack_id').val(rackId || $('#rack-filter option:selected').data('id') || '{{ $selectedRack?->id }}');
                $('#create_name_feedback').html('');
                new bootstrap.Modal(document.getElementById('create-shelf-modal')).show();
            }

            /**
             * After a drag in the list: stack the rack view's shelves in the same order.
             */
            function arrangeRackView(ids) {
                ids.forEach(function (id) {
                    const shelf = document.querySelector('.rack-shelf[data-id="' + id + '"]');
                    if (shelf) shelf.parentNode.appendChild(shelf);
                });
            }

            $(function () {
                // Remember which view was used last. Arriving with ?rack= opens the
                // list, since that is where the rack filter is.
                const wanted = {{ $selectedRack ? "'list'" : 'null' }} || localStorage.getItem('shelves.view');

                if (wanted === 'list') {
                    bootstrap.Tab.getOrCreateInstance(document.getElementById('list-view-tab')).show();
                }

                document.querySelectorAll('#shelf-view-tabs [data-bs-toggle="tab"]').forEach(function (tab) {
                    tab.addEventListener('shown.bs.tab', function () {
                        localStorage.setItem('shelves.view', tab.id === 'list-view-tab' ? 'list' : 'rack');
                    });
                });

                @can('edit shelves')
                    // Drag a shelf up or down its rack. Each rack is its own list, so a
                    // shelf can't be dropped into another rack (that is done from Edit).
                    document.querySelectorAll('.rack-frame').forEach(function (frame) {
                        Sortable.create(frame, {
                            handle: '.rack-grip',
                            draggable: '.rack-shelf[data-id]',
                            animation: 150,
                            onEnd: function (evt) {
                                if (evt.oldIndex === evt.newIndex) return;

                                const ids = Array.from(frame.querySelectorAll('.rack-shelf[data-id]'))
                                    .map((el) => Number(el.dataset.id));

                                $.post('{{ route('shelves.reorder') }}', {
                                    _token: '{{ csrf_token() }}',
                                    rack_id: frame.dataset.rackId,
                                    ids: ids,
                                })
                                    .done(function (res) {
                                        if (window.arrangeListView) window.arrangeListView(ids);
                                        Toast.fire({ icon: 'success', title: res.message });
                                    })
                                    .fail(function (xhr) {
                                        // Simplest safe undo: reload the saved order.
                                        Toast.fire({
                                            icon: 'error',
                                            title: (xhr.responseJSON && xhr.responseJSON.message) || 'Could not save the new order.',
                                        });
                                        setTimeout(() => location.reload(), 1500);
                                    });
                            },
                        });
                    });
                @endcan
            });

            function openEditModal(id, name, rackId) {
                document.getElementById('edit_shelf_id').value = id;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_rack_id').value = rackId;
                document.getElementById('edit_shelf_form').action = '/shelves/' + id;
                $('#edit_name').removeClass('is-valid is-invalid');
                $('#edit_name_feedback').html('');
                new bootstrap.Modal(document.getElementById('edit-shelf-modal')).show();
            }

            function closeModal(id) {
                const el = document.getElementById(id);
                const instance = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
                instance.hide();
            }
        </script>
    @endpush

@endsection