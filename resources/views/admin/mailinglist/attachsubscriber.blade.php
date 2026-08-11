@extends('layouts.admin.layout')

@section('content')
    <div class="">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ $prev_url }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Attach Subscriber</span>
        </h1>
        @include('partials.message')
        <attach-subscriber url="{{ url('/') }}" mode="admin" mailinglist_id="{{ $mailinglist_id }}">
        </attach-subscriber>
    </div>
@endsection
