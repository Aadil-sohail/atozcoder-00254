{{-- Reopened after a failed save of this form only: the "Add Rack" form on the
     same page also reports through the default error bag, marked by form=create_rack. --}}
@php($shelfCreateFailed = $errors->any() && ! $errors->updateShelf->isNotEmpty() && old('form') !== 'create_rack')
<div class="modal fade {{ $shelfCreateFailed ? 'show d-block' : '' }}"
    id="create-shelf-modal" tabindex="-1" aria-labelledby="createShelfLabel" aria-modal="true"
    style="{{ $shelfCreateFailed ? 'background:rgba(0,0,0,0.5)' : '' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('shelves.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createShelfLabel">{{ __('Add Row') }}</h5>
                    <button type="button" class="btn-close" onclick="closeModal('create-shelf-modal')"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_name" class="form-label">{{ __('Row Name') }} <span class="text-danger">*</span></label>
                        <input id="create_name" name="name" type="text" value="{{ $shelfCreateFailed ? old('name') : '' }}" required autofocus
                            class="form-control @error('name') is-invalid @enderror">
                        <div id="create_name_feedback" class="small mt-1"></div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="create_rack_id" class="form-label">{{ __('Shelf') }} <span class="text-danger">*</span></label>
                        <select id="create_rack_id" name="rack_id" required
                            class="form-select @error('rack_id') is-invalid @enderror">
                            <option value="">{{ __('Select a shelf') }}</option>
                            @foreach ($racks as $rack)
                                <option value="{{ $rack->id }}" @selected(old('rack_id') == $rack->id)>
                                    {{ $rack->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('rack_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('create-shelf-modal')" class="btn btn-outline-secondary">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-dark">{{ __('Save Row') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
