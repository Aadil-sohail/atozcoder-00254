<div class="modal fade {{ $errors->any() && !$errors->updateSubcategory->isNotEmpty() ? 'show d-block' : '' }}"
    id="create-subcategory-modal" tabindex="-1" aria-labelledby="createSubcategoryLabel" aria-modal="true"
    style="{{ $errors->any() && !$errors->updateSubcategory->isNotEmpty() ? 'background:rgba(0,0,0,0.5)' : '' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('subcategories.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createSubcategoryLabel">{{ __('Add Sub Category') }}</h5>
                    <button type="button" class="btn-close" onclick="closeModal('create-subcategory-modal')"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_name" class="form-label">{{ __('Sub Category Name') }}</label>
                        <input id="create_name" name="name" type="text" value="{{ old('name') }}" required autofocus
                            class="form-control @error('name') is-invalid @enderror">
                        <div id="create_name_feedback" class="small mt-1"></div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="create_category_id" class="form-label">{{ __('Main Category') }}</label>
                        <select id="create_category_id" name="category_id" required
                            class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">{{ __('Select a category') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('create-subcategory-modal')" class="btn btn-outline-secondary">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-dark">{{ __('Save Sub Category') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
