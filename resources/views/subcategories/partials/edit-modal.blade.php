<div class="modal fade {{ $errors->updateSubcategory->isNotEmpty() ? 'show d-block' : '' }}"
    id="edit-subcategory-modal" tabindex="-1" aria-labelledby="editSubcategoryLabel" aria-modal="true"
    style="{{ $errors->updateSubcategory->isNotEmpty() ? 'background:rgba(0,0,0,0.5)' : '' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST"
                id="edit_subcategory_form"
                action="{{ old('updateSubcategory_id') ? url('subcategories/' . old('updateSubcategory_id')) : '' }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="updateSubcategory_id" id="edit_subcategory_id" value="{{ old('updateSubcategory_id') }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="editSubcategoryLabel">{{ __('Edit Sub Category') }}</h5>
                    <button type="button" class="btn-close" onclick="closeModal('edit-subcategory-modal')"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">{{ __('Sub Category Name') }}</label>
                        <input id="edit_name" name="name" type="text" value="{{ old('name') }}" required
                            class="form-control @error('name', 'updateSubcategory') is-invalid @enderror">
                        <div id="edit_name_feedback" class="small mt-1"></div>
                        @error('name', 'updateSubcategory')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_category_id" class="form-label">{{ __('Main Category') }}</label>
                        <select id="edit_category_id" name="category_id" required
                            class="form-select @error('category_id', 'updateSubcategory') is-invalid @enderror">
                            <option value="">{{ __('Select a category') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id', 'updateSubcategory')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('edit-subcategory-modal')" class="btn btn-outline-secondary">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-dark">{{ __('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
