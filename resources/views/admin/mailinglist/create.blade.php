@extends('layouts.admin.layout')

@section('content')
    <div class="">
        <h1 class="admin-h1  flex items-center">
            <a href="{{ url('/admin/mailinglists') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('/uploads/icons/back.svg') }}" class="w-3 h-3 fill-current text-gray-700">
            </a>
            <span class="mx-3">Add Mailing List</span>
        </h1>
        @include('partials.message')
        <create-mailinglist mode="admin"></create-mailinglist>
    </div>
@endsection
