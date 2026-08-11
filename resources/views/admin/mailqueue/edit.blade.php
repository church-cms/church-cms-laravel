@extends('layouts.admin.layout')

@section('content')
    <div class="">
        <h1 class="admin-h1 my-3 flex items-center">
            <a href="{{ url('/admin/mailqueues') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('/uploads/icons/back.svg') }}" class="w-3 h-3 fill-current text-gray-700">
            </a>
            <span class="mx-3">Edit Mail Queue</span>
        </h1>
        @include('partials.message')
        <edit-queue id="{{ $mailqueue->id }}" mode="admin" date="{{ date('Y-m-d H:i:s') }}"></edit-queue>
    </div>
@endsection
