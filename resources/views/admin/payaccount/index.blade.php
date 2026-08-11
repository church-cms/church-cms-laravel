@extends('layouts.admin.layout')

@section('content')
    <div class="my-3">
        @include('partials.message')
        <payaccount-list url="{{ url('/') }}"></payaccount-list>
    </div>
@endsection
