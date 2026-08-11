@extends('layouts.admin.layout')
@section('content')
    <div class="w-full mx-2">
        <h1 class="admin-h1 mb-3 flex items-center">
            <a href="{{ url('/admin/preachers') }}" class="rounded-full bg-gray-100 p-2" title="Back">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Add Preacher</span>
        </h1>
        @include('partials.message')
        
        <form method="POST" action="" enctype="multipart/form-data">
            @csrf
            <create-preacher url="{{ url('/') }}"></create-preacher>
        </form>
    
    </div>
@endsection
