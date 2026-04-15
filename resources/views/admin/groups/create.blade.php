@extends('layouts.admin.layout')

@section('content')

    <div class=" ">
        <h1 class="admin-h1 mb-5 flex items-center">
            <a href="{{ url('/admin/groups') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Groups</span>
        </h1>
        @include('partials.message')
        <div class="bg-white shadow ">
            <create-group count="{{ $count }}">
            </create-group>
        </div>
    </div>

@endsection
