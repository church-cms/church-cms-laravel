@extends('layouts.admin.layout')
@section('content')
    <div class="">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ url('/admin/fund/show/' . $fund->id) }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Edit Fund</span>
        </h1>
        @include('partials.message')
        <div class="bg-white shadow px-3 py-3 my-3">
            <edit-fund url="{{ url('/') }}" id="{{ $fund->id }}"></edit-fund>
        </div>
    </div>
@endsection
