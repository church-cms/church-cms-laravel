@extends('layouts.admin.layout')
@section('content')
    <portal-target name="donation_header"></portal-target>
    <div class="bg-white my-3">
        @include('partials.message')
        <donation-list url="{{ url('/') }}"></donation-list>
    </div>
@endsection
