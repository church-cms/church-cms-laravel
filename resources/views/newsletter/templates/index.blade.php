@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div class="flex flex-wrap lg:flex-row justify-between">
            <div class="">
                <h1 class="admin-h1 my-3">Email Templates</h1>
            </div>
            <div class="relative flex items-center w-1/4 justify-end">
                <div class="flex items-center">
                    <a href="{{ url('/admin/campaign/add') }}"
                        class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center rounded-sm">
                        <span class="mx-1 text-sm font-semibold">+ New Template</span>

                    </a>
                </div>
            </div>
        </div>
        @include('partials.message')
        {{-- <campaign-list url="{{ url('/') }}" mode="admin"></campaign-list> --}}
    </div>
@endsection
