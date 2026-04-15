@extends('layouts.admin.layout')

@section('content')
    <div class="w-1/2 lg:mr-8 md:mr-8">
        <div>
            <h1 class="admin-h1 mb-5 flex items-center">
                <a href="{{ url('/admin/report/index') }}" title="Back" class="rounded-full bg-gray-300 p-2">
                    <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
                </a>
               
            </h1>
        </div>
        
    </div>
@endsection
