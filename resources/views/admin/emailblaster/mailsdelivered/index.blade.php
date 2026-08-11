@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div class="flex flex-wrap lg:flex-row justify-between">
            <div class="">
                <h1 class="admin-h1">Mails Delivered</h1>
            </div>
        </div>
        @include('partials.message')
        <list-mails-delivered url="{{ url('/') }}" mode="admin"></list-mails-delivered>
    </div>
@endsection
