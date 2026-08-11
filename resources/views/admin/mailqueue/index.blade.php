@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div class="flex flex-wrap lg:flex-row justify-between">
            <div class="">
                <h1 class="admin-h1">Mail Queue</h1>
            </div>
        </div>
        @include('partials.message')
        <div class="bg-white shadow p-2 my-3">
            <mailqueue url="{{ url('/') }}" mode="admin"></mailqueue>
        </div>
    </div>
@endsection
