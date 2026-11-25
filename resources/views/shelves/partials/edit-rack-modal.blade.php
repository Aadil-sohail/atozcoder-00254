{{-- Rename a rack, from the pencil on its heading. The form's action is set by
     openRenameRackModal(); after a failed save it is rebuilt from the old id. --}}
@php($rackEditFailed = $errors->updateRack->isNotEmpty())
<div class="modal fade {{ $rackEditFailed ? 'show d-block' : '' }}"
    id="edit-rack-modal" tabindex="-1" aria-labelledby="editRackLabel" aria-modal="true"
    style="{{ $rackEditFailed ? 'background:rgba(0,0,0,0.5)' : '' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="edit_rack_form"
                action="{{ $rackEditFailed && old('updateRack_id') ? route('racks.update', old('updateRack_id')) : '' }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="updateRack_id" id="edit_rack_id" value="{{ $rackEditFailed ? old('updateRack_id') : '' }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="editRackLabel">{{ __('Rename Rack') }}</h5>
                    <button type="button" class="btn-close" onclick="closeModal('edit-rack-modal')"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rack_edit_name" class="form-label">{{ __('Rack Name') }} <span class="text-danger">*</span></label>
                        <input id="rack_edit_name" name="name" type="text" required
                            value="{{ $rackEditFailed ? old('name') : '' }}"
                            class="form-control @error('name', 'updateRack') is-invalid @enderror">
                        <div id="rack_edit_name_feedback" class="small mt-1"></div>
                        @error('name', 'updateRack')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('edit-rack-modal')" class="btn btn-outline-secondary">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-dark">{{ __('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
