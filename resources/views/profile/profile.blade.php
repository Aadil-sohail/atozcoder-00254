@extends('layouts.header')

@section('title', 'Profile')

@section('content')
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('layouts.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @include('layouts.navbar')

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4">
                            <span class="text-muted fw-light">Setting /</span> Profile
                        </h4>

                        <!-- Navbar pills -->
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-pills flex-column flex-sm-row mb-4">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="javascript:void(0);">
                                            <i class="ti ti-user-check me-1 ti-xs"></i> Profile
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);">
                                            <i class="ti ti-lock me-1 ti-xs"></i> Password
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);">
                                            <i class="ti ti-building me-1 ti-xs"></i> Company
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);">
                                            <i class="ti ti-mail me-1 ti-xs"></i> Email
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--/ Navbar pills -->

                        <!-- User Profile -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <!-- User Avatar and Name -->
                                        <div class="d-flex align-items-center flex-column mb-4">
                                            <div class="avatar avatar-xl mb-3">
                                                <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Avatar"
                                                    class="rounded-circle" />
                                            </div>
                                            <h5 class="mb-0">{{ $user->name ?? 'User' }}</h5>
                                        </div>

                                        <!-- Profile Form -->
                                        <form id="formAccountSettings" method="POST"
                                            action="{{ route('profile.update') }}">
                                            @csrf
                                            @method('patch')

                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label for="firstName" class="form-label">First name <span
                                                            class="text-danger">*</span></label>
                                                    <input class="form-control" type="text" id="firstName" name="name"
                                                        value="{{ old('name', $user->name ?? '') }}"
                                                        placeholder="Enter your first name" autofocus />
                                                    @error('name')
                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="lastName" class="form-label">Last name</label>
                                                    <input class="form-control" type="text" name="lastname"
                                                        id="lastName" value="{{ old('lastname', $user->lastname ?? '') }}"
                                                        placeholder="Enter your last name" />
                                                    @error('lastname')
                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="email" class="form-label">Email <span
                                                            class="text-danger">*</span></label>
                                                    <input class="form-control" type="text" id="email" name="email"
                                                        value="{{ old('email', $user->email ?? '') }}"
                                                        placeholder="Enter your email" />
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
                        <!--/ User Profile -->
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('layouts.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
        <!-- / Layout container -->
    </div>
    <!-- / Layout wrapper -->
@endsection
@extends('layouts.footer')
