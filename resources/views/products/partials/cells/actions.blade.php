{{-- $hasEbayAccounts is passed in: the sync button is pointless with no store connected. --}}
<div class="d-flex align-items-left justify-content-start gap-2">
    <a href="{{ route('products.show', $product) }}"
        class="btn btn-sm btn-outline-secondary" title="{{ __('View') }}">
        <i class="fa-solid fa-eye"></i>
    </a>
    @can('sync ebay products')
        @if ($hasEbayAccounts)
        <button type="button" class="btn btn-sm btn-outline-dark" title="{{ __('Sync to eBay') }}"
            onclick="openEbaySyncModal({{ $product->id }})">
            <i class="fa-brands fa-ebay"></i>
        </button>
        @endif
    @endcan
    @can('edit products')
    <a href="{{ route('products.edit', $product) }}"
        class="btn btn-sm btn-outline-primary" title="{{ __('Edit') }}">
        <i class="fa-solid fa-pen"></i>
    </a>
    @endcan
    @can('delete products')
        @if ($product->status === '1')
            <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline"
                onsubmit="return confirm('{{ __('Disable this product?') }}');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Disable') }}">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        @endif
    @endcan
</div>
