{{-- Rack view: every rack drawn as a shelving unit, its shelves stacked top to
     bottom in their saved order. Drag a shelf by its grip to move it up or down
     its rack; the product count opens the shelf's page. --}}
@php($shelvesByRack = $shelves->groupBy('rack_id'))

@if ($racks->isEmpty())
    <div class="text-center text-muted py-5">
        <i class="fa-solid fa-warehouse fs-2 opacity-50 d-block mb-2"></i>
        {{ __('No racks yet. Use "Add Rack" to set up your first one.') }}
    </div>
@else
    <div class="rack-grid">
        @foreach ($racks as $rack)
            @php($rackShelves = $shelvesByRack->get($rack->id, collect()))

            <div class="rack-unit">
                {{-- Top bar: name, size and the rack's own buttons --}}
                <div class="rack-top">
                    <div class="text-truncate">
                        <i class="fa-solid fa-warehouse me-1 opacity-75"></i>
                        <span class="fw-semibold">{{ $rack->name }}</span>
                        <span class="rack-top-count">{{ $rackShelves->count() }} {{ Str::plural('shelf', $rackShelves->count()) }}</span>
                    </div>
                    <div class="d-flex gap-1 flex-shrink-0">
                        @can('create shelves')
                            <button type="button" class="rack-btn" title="{{ __('Add a shelf to this rack') }}"
                                onclick="openCreateModal({{ $rack->id }})"><i class="fa-solid fa-plus"></i></button>
                        @endcan
                        @can('edit racks')
                            <button type="button" class="rack-btn rack-rename" title="{{ __('Rename rack') }}"
                                data-id="{{ $rack->id }}" data-name="{{ $rack->name }}"><i class="fa-solid fa-pen"></i></button>
                        @endcan
                        @can('delete racks')
                            <button type="button" class="rack-btn rack-delete" title="{{ __('Delete rack') }}"
                                data-id="{{ $rack->id }}" data-name="{{ $rack->name }}" data-shelves="{{ $rackShelves->count() }}"><i class="fa-solid fa-trash"></i></button>
                        @endcan
                    </div>
                </div>

                {{-- The frame: two uprights, shelves in between --}}
                <div class="rack-frame" data-rack-id="{{ $rack->id }}">
                    @forelse ($rackShelves as $shelf)
                        <div class="rack-shelf" data-id="{{ $shelf->id }}">
                            <div class="rack-shelf-label">
                                @can('edit shelves')
                                    <i class="fa-solid fa-grip-vertical rack-grip" title="{{ __('Drag to reorder') }}"></i>
                                @endcan
                                <a href="{{ route('shelves.show', $shelf) }}" class="rack-shelf-name text-truncate" title="{{ __('Open this shelf') }}">{{ $shelf->name }}</a>
                                <a href="{{ route('shelves.show', $shelf) }}" class="rack-shelf-count text-decoration-none" title="{{ __('Products on this shelf') }}">
                                    {{ $shelf->products_count }} {{ Str::plural('product', $shelf->products_count) }}
                                </a>
                                <span class="ms-auto d-flex gap-1">
                                    @can('edit shelves')
                                        <button type="button" class="rack-btn rack-btn-sm" title="{{ __('Edit shelf') }}"
                                            onclick="openEditModal({{ $shelf->id }}, {{ json_encode($shelf->name) }}, {{ $shelf->rack_id }})"><i class="fa-solid fa-pen"></i></button>
                                    @endcan
                                    @can('delete shelves')
                                        <button type="button" class="rack-btn rack-btn-sm rack-btn-danger shelf-delete" title="{{ __('Delete shelf') }}"
                                            data-url="{{ route('shelves.destroy', $shelf) }}" data-name="{{ $shelf->name }}" data-products="{{ $shelf->products_count }}"><i class="fa-solid fa-trash"></i></button>
                                    @endcan
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="rack-shelf rack-shelf-none">
                            <span>{{ __('No shelves yet') }}</span>
                            @can('create shelves')
                                <button type="button" class="btn btn-sm btn-outline-dark mt-2" onclick="openCreateModal({{ $rack->id }})">
                                    <i class="fa-solid fa-plus me-1"></i>{{ __('Add Shelf') }}
                                </button>
                            @endcan
                        </div>
                    @endforelse
                </div>

                <div class="rack-feet"><span></span><span></span></div>
            </div>
        @endforeach
    </div>
@endif

@can('delete shelves')
    {{-- Sent by a shelf's trash button, after the confirm. --}}
    <form id="shelf-delete-form" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        <script>
            $(document).on('click', '.shelf-delete', function () {
                const name = this.dataset.name;
                const products = Number(this.dataset.products || 0);
                const url = this.dataset.url;

                // Same rule as the server: a shelf holding products stays.
                if (products > 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Shelf "' + name + '" is not empty',
                        text: 'It holds ' + products + (products === 1 ? ' product' : ' products') + '. Move them to another shelf first.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Delete shelf "' + name + '"?',
                    text: 'Its name will remain reserved in this rack.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                }).then(function (result) {
                    if (!result.isConfirmed) return;

                    const form = document.getElementById('shelf-delete-form');
                    form.action = url;
                    form.submit();
                });
            });
        </script>
    @endpush
@endcan
