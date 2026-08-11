@extends('layouts.admin.layout')

@section('content')
    <div class="">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ url('/admin/rules') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Add Rule</span>
        </h1>
        @include('partials.message')
        <create-rule url="{{ url('/') }}" mode="admin"></create-rule>
    </div>
@endsection
