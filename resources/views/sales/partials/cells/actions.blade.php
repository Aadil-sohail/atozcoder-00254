<div class="d-flex gap-2">
    <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-secondary">
        <i class="fa-solid fa-eye"></i>
    </a>
    @can('delete sales')
    <form method="POST" action="{{ route('sales.destroy', $sale) }}" class="d-inline"
        onsubmit="return confirm('{{ __('Delete this sale? This cannot be undone.') }}');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>
    @endcan
</div>
