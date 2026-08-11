@extends('layouts.admin.layout')

@section('content')
    <div class="">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ url('/admin/webhooks') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Add Webhook</span>
        </h1>
        @include('partials.message')
        <create-webhook url="{{ url('/') }}" mode="admin"></create-webhook>
    </div>
@endsection
