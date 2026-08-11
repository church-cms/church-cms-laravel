@extends('layouts.admin.layout')

@section('content')
    <div class="">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ url('/admin/emails') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Edit Email</span>
        </h1>
        @include('partials.message')
        <edit-email id="{{ $email->id }}" mode="admin"></edit-email>
    </div>
@endsection
