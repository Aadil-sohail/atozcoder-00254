@extends('layouts.header')

@section('title', 'Edit Product')

@section('header')
    <h2 class="text-lg font-semibold leading-tight text-gray-800">
        {{ __('Edit Product') }}
    </h2>
@endsection

@section('content')

    <div class="overflow-hidden rounded-lg bg-white p-6 shadow-sm sm:p-8">
        <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('products.partials.form')

            <div class="mt-4 d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-dark">{{ __('Save Changes') }}</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>

@endsection
