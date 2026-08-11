@extends('layouts.admin.layout')

@section('content')
    <section class="section">
        <div class="w-full">
            <div class="flex items-center justify-between">
                <div class="">
                    <h1 class="admin-h1 my-3">Feedbacks</h1>
                </div>
            </div>
            <div class="p-4 bg-white shadow-lg">
                <div class="w-full">
                    @include('partials.message')
                </div>
                @include('admin.feedbacks.list')
            </div>
        </div>
    </section>
@endsection
