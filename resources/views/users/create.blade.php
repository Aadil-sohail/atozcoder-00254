@extends('layouts.header')

@section('title', 'Add User')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('Add User') }}</h2>
@endsection

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                @include('users.partials.form')

                <div class="mt-4 d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-dark">{{ __('Create User') }}</button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>

@endsection
