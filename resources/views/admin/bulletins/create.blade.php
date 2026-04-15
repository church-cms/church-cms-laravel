@extends('layouts.admin.layout')

@section('content')
    <div class="w-full">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ url('/admin/bulletins') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Bulletin</span>
        </h1>
        <create-bulletin count="{{ $count }}" >
        </create-bulletin>
    </div>
@endsection
