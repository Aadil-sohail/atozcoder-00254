@extends('layouts.header')

@section('title', 'Dashboard')

@section('header')
    <h2 class="fs-5 fw-semibold mb-0">{{ __('Dashboard') }}</h2>
@endsection

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-body py-4">
            <p class="mb-0 text-muted">{{ __("You're logged in!") }} {{ auth()->user()->name }}</p>
        </div>
    </div>

@endsection
