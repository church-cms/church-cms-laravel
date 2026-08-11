@extends('layouts.admin.layout')
@section('content')
    <div class="flex flex-row justify-between">
        <div class="w-1/2">
            <h1 class="admin-h1 my-3">Advanced SEO Settings</h1>
        </div>
    </div>
    <div class="bg-white py-2">
        <div class="mx-6">
            @include('partials.message')
        </div>
        @include('admin.settings.advanced_seo')
    </div>
@endsection
