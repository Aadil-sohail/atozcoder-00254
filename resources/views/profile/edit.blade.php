@extends('layouts.header')

@section('title', 'Profile')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('Profile Setting') }}</h2>
@endsection

@section('content')

    {{-- Nav Pills --}}
    <ul class="nav nav-pills flex-column flex-sm-row mb-4">
        <li class="nav-item">
            <button type="button" class="nav-link active" data-bs-toggle="tab"
                data-bs-target="#navs-pills-top-home">
                <i class="fa-solid fa-user me-1"></i> Profile
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" data-bs-toggle="tab"
                data-bs-target="#navs-pills-top-password">
                <i class="fa-solid fa-lock me-1"></i> Password
            </button>
        </li>
        @can('edit company settings')
            <li class="nav-item">
                <button type="button" class="nav-link" data-bs-toggle="tab"
                    data-bs-target="#navs-pills-top-company">
                    <i class="fa-solid fa-building me-1"></i> Company
                </button>
            </li>
        @endcan
        @can('edit smtp settings')
            <li class="nav-item">
                <button type="button" class="nav-link" data-bs-toggle="tab"
                    data-bs-target="#navs-pills-top-email">
                    <i class="fa-solid fa-envelope me-1"></i> Email (SMTP)
                </button>
            </li>
        @endcan
    </ul>

    {{-- Tab Content --}}
    <div class="tab-content">
        @include('profile.sections.profile-section')
        @include('profile.sections.password-section')
        @can('edit company settings')
            @include('profile.sections.company-section')
        @endcan
        @can('edit smtp settings')
            @include('profile.sections.email-section')
        @endcan
    </div>

    @include('profile.sections.profile-scripts')

@endsection
