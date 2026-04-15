@extends('layouts.admin.layout')

@section('content')

    <div class="w-full lg:w-11/12">
        <div>
            <h1 class="admin-h1 mb-5 flex items-center">
                <a href="{{ url('/pricing') }}" title="Back" class="rounded-full bg-gray-300 p-2">
                    <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
                </a>
                <span class="mx-3">Payment</span>
            </h1>
        </div>

        <div class="py-2">Payment is Successfull !!!!</div>

        
    </div>

@endsection
