<div class="modal fade {{ $errors->updateCategory->isNotEmpty() ? 'show d-block' : '' }}"
    id="edit-category-modal" tabindex="-1" aria-labelledby="editCategoryLabel" aria-modal="true"
    style="{{ $errors->updateCategory->isNotEmpty() ? 'background:rgba(0,0,0,0.5)' : '' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST"
                id="edit_category_form"
                action="{{ old('updateCategory_id') ? url('categories/' . old('updateCategory_id')) : '' }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="updateCategory_id" id="edit_category_id" value="{{ old('updateCategory_id') }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryLabel">{{ __('Edit Category') }}</h5>
                    <button type="button" class="btn-close" onclick="closeModal('edit-category-modal')"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">{{ __('Category Name') }}</label>
                        <input id="edit_name" name="name" type="text" value="{{ old('name') }}" required
                            class="form-control @error('name', 'updateCategory') is-invalid @enderror">
                        <div id="edit_name_feedback" class="small mt-1"></div>
                        @error('name', 'updateCategory')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('edit-category-modal')" class="btn btn-outline-secondary">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-dark">{{ __('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
