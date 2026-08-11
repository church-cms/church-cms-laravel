@extends('layouts.admin.layout')

@section('content')
    <div class="w-full lg:w-11/12">
        <div>
            <h1 class="admin-h1 mb-5 flex items-center">
                <a href="{{ url('/admin/guest/show/' . $user->name) }}" title="Back" class="rounded-full bg-gray-100 p-2">
                    <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
                </a>
                <span class="mx-3">Exit Guest</span>
            </h1>
        </div>
        @include('partials.message')
        <exit-member url="{{ url('/') }}" name="{{ $user->name }}"></exit-member>
    </div>
@endsection
