@extends('layouts.admin.layout')

@section('content')
    <div class="">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ url('/admin/smtps') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('/uploads/icons/back.svg') }}" class="w-3 h-3 fill-current text-gray-700">
            </a>
            <span class="mx-3">Create Smtp</span>
        </h1>
        @include('partials.message')
        <create-smtp mode="admin"></create-smtp>
    </div>
@endsection
