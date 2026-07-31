<div class="d-flex gap-2">
    <a href="{{ route('returns.show', $return) }}" class="btn btn-sm btn-outline-secondary">
        <i class="fa-solid fa-eye"></i>
    </a>
    @can('delete returns')
    <form method="POST" action="{{ route('returns.destroy', $return) }}" class="d-inline"
        onsubmit="return confirmDelete(event, {{ json_encode(__('Delete this return? Any restocked quantity will be removed again.')) }});">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>
    @endcan
</div>
