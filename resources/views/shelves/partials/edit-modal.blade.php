<div class="modal fade {{ $errors->updateShelf->isNotEmpty() ? 'show d-block' : '' }}"
    id="edit-shelf-modal" tabindex="-1" aria-labelledby="editShelfLabel" aria-modal="true"
    style="{{ $errors->updateShelf->isNotEmpty() ? 'background:rgba(0,0,0,0.5)' : '' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST"
                id="edit_shelf_form"
                action="{{ old('updateShelf_id') ? url('shelves/' . old('updateShelf_id')) : '' }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="updateShelf_id" id="edit_shelf_id" value="{{ old('updateShelf_id') }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="editShelfLabel">{{ __('Edit Row') }}</h5>
                    <button type="button" class="btn-close" onclick="closeModal('edit-shelf-modal')"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">{{ __('Row Name') }} <span class="text-danger">*</span></label>
                        <input id="edit_name" name="name" type="text" value="{{ old('name') }}" required
                            class="form-control @error('name', 'updateShelf') is-invalid @enderror">
                        <div id="edit_name_feedback" class="small mt-1"></div>
                        @error('name', 'updateShelf')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_rack_id" class="form-label">{{ __('Shelf') }} <span class="text-danger">*</span></label>
                        <select id="edit_rack_id" name="rack_id" required
                            class="form-select @error('rack_id', 'updateShelf') is-invalid @enderror">
                            <option value="">{{ __('Select a shelf') }}</option>
                            @foreach ($racks as $rack)
                                <option value="{{ $rack->id }}" @selected(old('rack_id') == $rack->id)>
                                    {{ $rack->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('rack_id', 'updateShelf')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('edit-shelf-modal')" class="btn btn-outline-secondary">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-dark">{{ __('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
