@extends('layouts.admin.layout')

@section('content')
    <div class="">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ url('/admin/subscribers') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Edit Subscriber</span>
        </h1>
        @include('partials.message')
        <edit-subscriber url="{{ url('/') }}" id="{{ $subscriber->id }}" mode="admin"></edit-subscriber>
    </div>
@endsection
