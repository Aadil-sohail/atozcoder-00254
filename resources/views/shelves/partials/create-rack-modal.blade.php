{{-- "Add Rack" on the Shelves page, where racks and shelves are both managed.
     Its fields carry their own ids, and form=create_rack tells a failed save of
     this form from one of "Add Shelf" (both report through the default bag). --}}
@php($rackCreateFailed = $errors->any() && old('form') === 'create_rack')
<div class="modal fade {{ $rackCreateFailed ? 'show d-block' : '' }}"
    id="create-rack-modal" tabindex="-1" aria-labelledby="createRackLabel" aria-modal="true"
    style="{{ $rackCreateFailed ? 'background:rgba(0,0,0,0.5)' : '' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('racks.store') }}">
                @csrf
                <input type="hidden" name="form" value="create_rack">

                <div class="modal-header">
                    <h5 class="modal-title" id="createRackLabel">{{ __('Add Rack') }}</h5>
                    <button type="button" class="btn-close" onclick="closeModal('create-rack-modal')"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rack_create_name" class="form-label">{{ __('Rack Name') }} <span class="text-danger">*</span></label>
                        <input id="rack_create_name" name="name" type="text" required
                            value="{{ $rackCreateFailed ? old('name') : '' }}"
                            class="form-control {{ $rackCreateFailed && $errors->has('name') ? 'is-invalid' : '' }}">
                        <div id="rack_create_name_feedback" class="small mt-1"></div>
                        @if ($rackCreateFailed && $errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                    <p class="text-muted small mb-0">{{ __('Once the rack is saved, use "Add Shelf" to give it shelves.') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('create-rack-modal')" class="btn btn-outline-secondary">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-dark">{{ __('Save Rack') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
