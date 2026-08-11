@extends('layouts.admin.layout')

@section('content')
    <portal-target name="add_bulletin"></portal-target>
    <div class="bg-white my-3">
        @include('partials.message')
        <bulletin-tab url="{{ url('/') }}"></bulletin-tab>
        <portal-target name="list_bulletin"></portal-target>
    </div>
@endsection
