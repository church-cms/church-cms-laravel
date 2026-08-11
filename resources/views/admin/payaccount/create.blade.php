@extends('layouts.admin.layout')
@section('content')
    <div class="w-full">
        <h1 class="admin-h1 mb-5 flex items-center">
            <a href="{{ url('/admin/payaccounts') }}" title="Back" class="rounded-full bg-gray-300 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Create Payaccount</span>
        </h1>
        @include('partials.message')
        <create-payaccount url="{{ url('/') }}">
            </create-create-tab>
    </div>
@endsection
