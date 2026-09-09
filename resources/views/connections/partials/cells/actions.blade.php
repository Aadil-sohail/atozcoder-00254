<div class="d-flex gap-2">
    @can('edit connections')
        <a href="{{ route('connections.edit', $connection) }}" class="btn btn-sm btn-outline-primary"
           title="{{ __('Edit') }}">
            <i class="fa-solid fa-pen"></i>
        </a>
    @endcan
    @can('delete connections')
        <form method="POST" action="{{ route('connections.destroy', $connection) }}" class="d-inline"
              onsubmit="return confirm('{{ __('Break this connection? Its products go back to counting separately.') }}');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Break connection') }}">
                <i class="fa-solid fa-link-slash"></i>
            </button>
        </form>
    @endcan
</div>
