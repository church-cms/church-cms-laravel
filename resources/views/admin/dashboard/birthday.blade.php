@extends('layouts.admin.layout')

@section('content')

    <div class="add-member">
        <h1 class="admin-h1 mb-5 flex items-center">
            <a href="{{ url('/admin/dashboard') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Send Message</span>
        </h1>
    </div>

    @include('partials.message')

    <div class="tw-form-group full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
                <birthday url="{{ url('/') }}"></birthday>
            </div>
        </div>
    </div>

@endsection
