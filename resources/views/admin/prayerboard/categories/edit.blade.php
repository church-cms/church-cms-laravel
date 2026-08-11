@extends('layouts.admin.layout')

@section('content')
<div class="flex flex-row justify-between items-center mb-4">
    <div>
        <a href="{{ url('/admin/prayercategories') }}" class="text-sm text-indigo-600 hover:underline">← Categories</a>
        <h1 class="admin-h1 mt-1">Edit: {{ $category->emoji }} {{ $category->name }}</h1>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <form method="POST" action="{{ url('/admin/prayercategory/edit/' . $category->id) }}">
        @csrf @method('PUT')
        @include('admin.prayerboard.categories._form', ['category' => $category])
        <div class="mt-6 flex gap-3">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ url('/admin/prayercategories') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
