{{-- Where the product is kept, "Rack › Shelf"; links to everything on that shelf. --}}
@if ($product->shelf_label)
    @can('view shelves')
        <a href="{{ route('shelves.show', $product->shelf_id) }}" class="text-decoration-none">{{ $product->shelf_label }}</a>
    @else
        {{ $product->shelf_label }}
    @endcan
@else
    <span class="text-muted">—</span>
@endif
