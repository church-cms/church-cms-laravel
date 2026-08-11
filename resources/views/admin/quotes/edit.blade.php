@extends('layouts.admin.layout')
@section('content')
    <div class="relative">
        <div class="flex flex-wrap lg:flex-row justify-between">
            <div class="">
                <h1 class="admin-h1 flex items-center">
                    <a href="{{ url('/admin/quotes') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                        <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
                    </a>
                    <span class="mx-3">Edit Quote</span>
                </h1>
            </div>
        </div>
        <div class="bg-white my-3">
            @include('partials.message')
            <add-quote-tab url="{{ url('/') }}" id="{{ $quote->id }}" mode="edit"></add-quote-tab>
            <portal-target name="edit_quote_tab"></portal-target>
        </div>
    </div>
@endsection
