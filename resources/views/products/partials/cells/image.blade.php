@if (! empty($product->image))
    <img src="{{ asset($product->image[0]) }}" alt="{{ $product->name }}"
        class="rounded" style="width:40px; height:40px; object-fit:cover;">
@else
    <div class="d-flex align-items-center justify-content-center rounded bg-light"
        style="width:40px; height:40px;">
        <i class="fa-solid fa-image text-secondary"></i>
    </div>
@endif
