@extends('layouts.member')

@section('title', 'Change Password')

@section('content')

<div class="mb-6 mx-auto max-w-[512px]">
    <a href="{{ url('/member/home') }}"
        class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 no-underline mb-4">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Profile
    </a>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <change-password url="{{ url('/') }}"></change-password>
    </div>
</div>


@endsection
