<a href="{{ route('products.show', $product->id) }}" class="fw-medium text-dark text-decoration-none">
    {{ $product->name }}
</a>
@if ($product->sku || $product->variant || $product->subcategory_name)
    <div class="text-muted" style="font-size:11px;">
        {{ collect([$product->sku, $product->variant, $product->subcategory_name])->filter()->implode(' · ') }}
    </div>
@endif
