@extends('layouts.admin.layout')

@section('content')
    <div class="">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ $prev_url }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Attach Mailing List</span>
        </h1>
        @include('partials.message')
        <attach-maillist-subscriber url="{{ url('/') }}" mode="admin" subscriber_id="{{ $subscriber_id }}">
        </attach-maillist-subscriber>
    </div>
@endsection
