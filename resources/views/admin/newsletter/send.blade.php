@extends('layouts.admin.layout')
@section('content')
    <div class="w-full">
        <h1 class="admin-h1  flex items-center">
            <span class="">News Letter</span>
        </h1>
        @include('partials.message')
        <newsletter-send url="{{ url('/') }}"></newsletter-send>
    </div>
@endsection
