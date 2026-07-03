<!-- Company Tab -->
<div class="tab-pane fade" id="navs-pills-top-company" role="tabpanel">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <form id="formCompanySettings" method="POST" action="{{ route('company.update') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        @if (session('status') === 'company-updated')
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Data updated successfully!
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row">
                            <!-- Company Logo -->
                            <div class="mb-3 col-md-6 d-flex justify-content-center flex-column align-items-center">
                                <label class="form-label mb-3">Logo</label>
                                <div class="company-logo-wrapper">
                                    <input type="file" class="dropify" id="company_logo" name="company_logo"
                                        data-default-file="{{ old('company_logo_preview', $company && $company->company_logo ? asset($company->company_logo) : '') }}"
                                        data-allowed-file-extensions="jpg jpeg png gif" data-max-file-size="800K"
                                        accept="image/*" />
                                    @error('company_logo')
                                        <div class="text-danger small mt-1 text-center">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Fav Icon -->
                            <div class="mb-3 col-md-6 d-flex justify-content-center flex-column align-items-center">
                                <label class="form-label mb-3">Fav Icon</label>
                                <div class="fav-icon-wrapper">
                                    <input type="file" class="dropify" id="fav_icon" name="fav_icon"
                                        data-default-file="{{ old('fav_icon_preview', $company && $company->fav_icon ? asset($company->fav_icon) : '') }}"
                                        data-allowed-file-extensions="jpg jpeg png gif ico" data-max-file-size="800K"
                                        accept="image/*" />
                                    @error('fav_icon')
                                        <div class="text-danger small mt-1 text-center">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Company Name -->
                            <div class="mb-3 col-md-6">
                                <label for="company_name" class="form-label">Company name
                                    <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="company_name" name="company_name"
                                    value="{{ old('company_name', $company->company_name ?? '') }}"
                                    placeholder="Enter company name" />
                                @error('company_name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Company Email -->
                            <div class="mb-3 col-md-6">
                                <label for="company_email" class="form-label">Company email
                                    <span class="text-danger">*</span></label>
                                <input class="form-control" type="email" id="company_email" name="company_email"
                                    value="{{ old('company_email', $company->company_email ?? '') }}"
                                    placeholder="Enter company email" />
                                @error('company_email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Company Phone -->
                            <div class="mb-3 col-md-6">
                                <label for="company_phone" class="form-label">Company Phone
                                    <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="company_phone" name="company_phone"
                                    value="{{ old('company_phone', $company->company_phone ?? '') }}"
                                    placeholder="Enter company phone" />
                                @error('company_phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Company Mobile -->
                            <div class="mb-3 col-md-6">
                                <label for="company_mobile" class="form-label">Company mobile
                                    <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="company_mobile" name="company_mobile"
                                    value="{{ old('company_mobile', $company->company_mobile ?? '') }}"
                                    placeholder="Enter company mobile" />
                                @error('company_mobile')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Company Address -->
                            <div class="mb-3 col-md-12">
                                <label for="company_address" class="form-label">Company
                                    address <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="company_address" name="company_address" rows="3"
                                    placeholder="Please enter company address">{{ old('company_address', $company->company_address ?? '') }}</textarea>
                                @error('company_address')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Update</button>
                            <button type="reset" class="btn btn-label-secondary">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Company Tab -->
