@extends('layouts.admin.layout')

@section('content')
    <div class="add-member">
        <h1 class="admin-h1 mb-5 flex items-center">
            <a href="{{ url('/admin/group/show/' . $group->id) }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Add Member to Group</span>
        </h1>
    </div>

    @include('partials.message')

    <div class="tw-form-group bg-white shadow p-3 py-3">
        <div class="">
            <div class="mb-2">
                <add-groupmember url="{{ url('/') }}" church_id="{{ $group->church_id }}"
                    group_id="{{ $group->id }}"></add-groupmember>
            </div>
        </div>
    </div>

@endsection
