<!-- Profile Tab -->
<div class="tab-pane fade show active" id="navs-pills-top-home" role="tabpanel">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <!-- User Name -->
                    <div class="d-flex align-items-center flex-column mb-4">
                        <h5 class="mb-0">{{ $user->name . ' ' . $user->lastname ?? 'User' }}</h5>
                    </div>

                    <!-- Profile Form -->
                    <form id="formAccountSettings" method="POST" action="{{ route('profile.update') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        @if (session('status') === 'profile-updated')
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Profile updated successfully!
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row">
                            <!-- Profile Picture -->
                            <div class="mb-3 col-md-12 d-flex justify-content-center">
                                <div class="profile-picture-wrapper">
                                    <input type="file" class="dropify" id="profile_picture" name="avatar"
                                        data-default-file="{{ old('avatar_preview', $user->user_image ? asset('storage/' . $user->user_image) : '') }}"
                                        data-allowed-file-extensions="jpg jpeg png gif" data-max-file-size="2M"
                                        accept="image/*" />
                                    @error('avatar')
                                        <div class="text-danger small mt-1 text-center">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="firstName" class="form-label">First name <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="firstName" name="name"
                                    value="{{ old('name', $user->name ?? '') }}" placeholder="Enter your first name"
                                    autofocus />
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="lastName" class="form-label">Last name</label>
                                <input class="form-control" type="text" id="lastName" name="lastname"
                                    value="{{ old('lastname', $user->lastname ?? '') }}"
                                    placeholder="Enter your last name" />
                                @error('lastname')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">Email <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="email" name="email"
                                    value="{{ old('email', $user->email ?? '') }}" placeholder="Enter your email" />
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="phone" class="form-label">Phone number</label>
                                <input type="text" id="phone" name="phone" class="form-control"
                                    value="{{ old('phone', $user->phone ?? '') }}"
                                    placeholder="Enter your phone number" />
                                @error('phone')
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
<!--/ Profile Tab -->
