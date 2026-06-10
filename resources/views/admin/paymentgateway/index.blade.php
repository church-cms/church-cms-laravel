@extends('layouts.admin.layout')

@section('content')
    <div class="my-3">
        @include('partials.message')
        <paymentgateway-list url="{{ url('/') }}"></paymentgateway-list>
    </div>
@endsection
