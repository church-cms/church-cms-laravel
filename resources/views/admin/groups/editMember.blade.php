@extends('layouts.admin.layout')

@section('content')
<div class="w-full max-w-lg">

    <h1 class="admin-h1 mb-5 flex items-center gap-3">
        <a href="{{ url('/admin/group/show/' . $member->group_id) }}" class="rounded-full bg-gray-100 p-2 hover:bg-gray-200 transition">
            <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
        </a>
        Edit Group Member
    </h1>

    @include('partials.message')

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">

        @php
            $profile = $member->user?->userprofile;
            $fullname = $profile ? trim($profile->firstname . ' ' . $profile->lastname) : $member->user?->name;
        @endphp
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                <span class="text-indigo-600 text-sm font-semibold">{{ strtoupper(substr($fullname ?? '?', 0, 1)) }}</span>
            </div>
            <div>
                <p class="font-medium text-gray-800">{{ $fullname }}</p>
                <p class="text-xs text-gray-400">{{ $member->user?->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ url('/admin/group/editMember/' . $member->id) }}">
            @csrf

            <div class="mb-5">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Role</label>
                <select name="role" class="tw-form-control w-full">
                    <option value="group_admin" {{ $member->role === 'group_admin' ? 'selected' : '' }}>Group Admin</option>
                    <option value="member"      {{ $member->role === 'member'      ? 'selected' : '' }}>Member</option>
                    <option value="guest"       {{ $member->role === 'guest'       ? 'selected' : '' }}>Guest</option>
                </select>
                <p class="text-xs text-gray-400 mt-1">
                    <strong>Group Admin</strong> – add/remove members &amp; message &nbsp;|&nbsp;
                    <strong>Member</strong> – message only &nbsp;|&nbsp;
                    <strong>Guest</strong> – view only
                </p>
            </div>

            <button type="submit" class="blue-bg text-white text-sm px-5 py-2 rounded">
                Update Role
            </button>
        </form>
    </div>

</div>
@endsection
