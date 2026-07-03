@extends('layouts.header')

@section('title', 'Add Product')

@section('header')
    <h2 class="text-lg font-semibold leading-tight text-gray-800">
        {{ __('Add Product') }}
    </h2>
@endsection

@section('content')

    <div class="overflow-hidden rounded-lg bg-white">
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf

            @include('products.partials.form')

            <div class="mt-4 d-flex align-items-center gap-3 p-3">
                <button type="submit" class="btn btn-dark">{{ __('Save Product') }}</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>

@endsection
