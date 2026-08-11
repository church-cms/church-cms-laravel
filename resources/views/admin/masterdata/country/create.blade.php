@extends('layouts.admin.layout')

@section('content')
<div class="w-full">
    <h1 class="admin-h1 mb-5 flex items-center">
        <a href="{{ url('/admin/countries') }}" title="Back" class="rounded-full bg-gray-100 p-2">
            <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
        </a>
        <span class="mx-3">Add Country</span>
    </h1>
    <form method="POST" action="{{ url('/admin/country/create') }}">
        @csrf
        @include('admin.masterdata.country._form')
    </form>
</div>
@endsection
