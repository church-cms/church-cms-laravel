@extends('layouts.admin.layout')
@section('content')
    <portal-target name="add_fund"></portal-target>
    <div class="bg-white my-3">
        @include('partials.message')
        <list-fund url="{{ url('/') }}"></list-fund>
    </div>
@endsection
