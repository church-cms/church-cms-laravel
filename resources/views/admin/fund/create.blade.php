@extends('layouts.admin.layout')
@section('content')
    <div class="">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ url('/admin/funds') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Add Fund</span>
        </h1>
        <div class="bg-white shadow px-3 py-3 my-3">
            @include('partials.message')
            <add-fund url="{{ url('/') }}"></add-fund>
        </div>
    </div>
@endsection
