<div class="modal fade {{ $errors->any() && !$errors->updateCategory->isNotEmpty() ? 'show d-block' : '' }}"
    id="create-category-modal" tabindex="-1" aria-labelledby="createCategoryLabel" aria-modal="true"
    style="{{ $errors->any() && !$errors->updateCategory->isNotEmpty() ? 'background:rgba(0,0,0,0.5)' : '' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createCategoryLabel">{{ __('Add Category') }}</h5>
                    <button type="button" class="btn-close" onclick="closeModal('create-category-modal')"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_name" class="form-label">{{ __('Category Name') }}</label>
                        <input id="create_name" name="name" type="text" value="{{ old('name') }}" required autofocus
                            class="form-control @error('name') is-invalid @enderror">
                        <div id="create_name_feedback" class="small mt-1"></div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('create-category-modal')" class="btn btn-outline-secondary">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-dark">{{ __('Save Category') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
