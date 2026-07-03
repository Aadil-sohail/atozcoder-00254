<!-- Email Tab -->
<div class="tab-pane fade" id="navs-pills-top-email" role="tabpanel">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <form id="formEmailSettings" method="POST" action="{{ route('smtp-setting.update') }}">
                        @csrf
                        @method('patch')

                        @if (session('status') === 'email-updated')
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Email settings updated successfully!
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row">
                            <!-- Mailer -->
                            <div class="mb-3 col-md-6">
                                <label for="mailer" class="form-label">Mailer <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="mailer" name="mailer"
                                    value="{{ old('mailer', $smtpSetting->mailer ?? 'smtp') }}"
                                    placeholder="Enter mailer" />
                                @error('mailer')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Host -->
                            <div class="mb-3 col-md-6">
                                <label for="host" class="form-label">Host <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="host" name="host"
                                    value="{{ old('host', $smtpSetting->host ?? '') }}" placeholder="Enter host" />
                                @error('host')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Username -->
                            <div class="mb-3 col-md-6">
                                <label for="username" class="form-label">Username <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="username" name="username"
                                    value="{{ old('username', $smtpSetting->username ?? '') }}"
                                    placeholder="Enter username" />
                                @error('username')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Port -->
                            <div class="mb-3 col-md-6">
                                <label for="port" class="form-label">Port <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="port" name="port"
                                    value="{{ old('port', $smtpSetting->port ?? '') }}" placeholder="Enter port" />
                                @error('port')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Encryption -->
                            <div class="mb-3 col-md-6">
                                <label for="encryption" class="form-label">Encryption <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="encryption" name="encryption"
                                    value="{{ old('encryption', $smtpSetting->encryption ?? '') }}"
                                    placeholder="Enter encryption" />
                                @error('encryption')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3 col-md-6">
                                <label for="password" class="form-label">Password <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="password" name="password"
                                    value="{{ old('password', $smtpSetting->password ?? '') }}"
                                    placeholder="Enter password" />
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- From Address -->
                            <div class="mb-3 col-md-12">
                                <label for="from_address" class="form-label">From Address
                                    <span class="text-danger">*</span></label>
                                <input class="form-control" type="email" id="from_address" name="from_address"
                                    value="{{ old('from_address', $smtpSetting->from_address ?? '') }}"
                                    placeholder="Enter from address" />
                                @error('from_address')
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
<!--/ Email Tab -->
