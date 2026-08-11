@extends('layouts.admin.layout')

@section('content')
<div class="flex flex-row justify-between items-center mb-4">
    <div>
        <a href="{{ url('/admin/prayercategories') }}" class="text-sm text-indigo-600 hover:underline">← Categories</a>
        <h1 class="admin-h1 mt-1">New Prayer Category</h1>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <form method="POST" action="{{ url('/admin/prayercategory/create') }}">
        @csrf
        @include('admin.prayerboard.categories._form')
        <div class="mt-6 flex gap-3">
            <button type="submit" class="btn btn-primary">Create Category</button>
            <a href="{{ url('/admin/prayercategories') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
