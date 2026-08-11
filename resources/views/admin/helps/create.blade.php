@extends('layouts.admin.layout')

@section('content')
    <div class="w-full">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ url('/admin/helps') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Add Help</span>
        </h1>
        @include('partials.message')
        <div class="bg-white shadow px-4 py-2 my-3">
            <create-help url="{{ url('/') }}"></create-help>
        </div>
    </div>
@endsection
