@extends('layouts.admin.layout')

@section('content')
    <div class="w-full">
        <h1 class="admin-h1 mb-5 flex items-center">
            <a href="{{ url('/admin/preacher/show/' . $user->name) }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Edit Preacher</span>
        </h1>
        @include('partials.message')
        <div class="bg-white shadow px-4 py-2">
            <edit-preacher url="{{ url('/') }}" name="{{ $user->name }}" mode="admin\preacher"></edit-preacher>
        </div>
    </div>
@endsection

<style>
    .tw-label {
        color: #3492e2;
    }

    .tw-label input[type="file"] {
        display: none;
    }

</style>
