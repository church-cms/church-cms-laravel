@extends('layouts.admin.layout')
@section('content')
    <div class="w-full">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ url('/admin/mediafiles') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Add Audio</span>
        </h1>
        @include('partials.message')
        <div class="bg-white shadow my-3">
            <create-audio url="{{ url('/') }}" csrf="{{ csrf_token() }}"></create-audio>
            {{-- @include('admin.mediafiles.audio.create_form') --}}
        </div>
    </div>
@endsection
