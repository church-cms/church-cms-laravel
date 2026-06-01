@extends('layouts.admin.layout')

@section('content')
@php
$isAdmin = auth()->user()->usergroup_id == 3;
@endphp

{{-- Page header --}}
<div class="flex items-center justify-between my-3">
    <h1 class="admin-h1">Groups ({{ $count }})</h1>
    @if($isAdmin || Auth::user()->hasPermission('create-groups'))
    <a href="{{ url('/admin/group/create') }}"
        class="text-sm rounded px-3 py-1.5 flex items-center gap-2 btn btn-primary submit-btn">
        <i class="fas fa-plus text-xs"></i>
        <span>Create Group</span>
    </a>
    @endif
</div>

@include('partials.message')

<div class="py-5 bg-white shadow px-3">

    {{-- Search bar --}}
    <form method="GET" action="{{ url('/admin/groups') }}" class="flex items-center gap-2 mb-4">
        <div class="relative flex-1 max-w-xs">
            <input type="text" name="search"
                value="{{ request('search') }}"
                placeholder="Search by name or description…"
                class="tw-form-control w-full pr-8 text-sm">
            <button type="submit" class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-600">
                <i class="fas fa-search text-xs"></i>
            </button>
        </div>
        @if(request('search'))
        <a href="{{ url('/admin/groups') }}"
            class="text-sm border bg-gray-100 text-gray-600 py-1.5 px-3 rounded hover:bg-gray-200 transition">
            Reset
        </a>
        @endif
    </form>

    @if($groups->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <i class="fas fa-users text-4xl mb-3 block"></i>
        <p class="text-sm">No groups yet.</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    <th class="px-4 py-3 text-left w-12"></th>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Description</th>
                    <th class="px-4 py-3 text-left">Category</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($groups as $group)
                <tr class="hover:bg-gray-50 transition">

                    {{-- Cover thumbnail --}}
                    <td class="px-4 py-3">
                        <img src="{{ $group->CoverImagePath }}" alt="{{ $group->name }}"
                            class="w-10 h-10 rounded object-cover flex-shrink-0">
                    </td>

                    {{-- Name --}}
                    <td class="px-4 py-3">
                        <a href="{{ url('/admin/group/show/' . $group->id) }}"
                            class="font-medium text-gray-800 hover:text-blue-600 transition capitalize">
                            {{ $group->name }}
                        </a>
                    </td>

                    {{-- Description --}}
                    <td class="px-4 py-3 text-gray-500">
                        {{ \Str::limit($group->description, 60) ?: '—' }}
                    </td>

                    {{-- Category --}}
                    <td class="px-4 py-3 text-gray-500">
                        {{ optional($group->groupCategory)->name ?? '—' }}
                    </td>

                    {{-- Type --}}
                    <td class="px-4 py-3">
                        @if($group->group_type)
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full capitalize">
                            {{ $group->group_type }}
                        </span>
                        @else
                        <span class="text-gray-400">—</span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ url('/admin/group/show/' . $group->id) }}"
                                style="display:inline-flex;align-items:center;justify-content:center;width:62px;height:28px;font-size:12px;font-weight:500;border-radius:4px;border:1px solid #3b82f6;color:#2563eb;background:#eff6ff;text-decoration:none;box-sizing:border-box;">
                                View
                            </a>
                            @if($isAdmin || Auth::user()->hasPermission('create-groups'))
                            <a href="{{ url('/admin/group/edit/' . $group->id) }}"
                                style="display:inline-flex;align-items:center;justify-content:center;width:62px;height:28px;font-size:12px;font-weight:500;border-radius:4px;border:1px solid #6b7280;color:#374151;background:#f3f4f6;text-decoration:none;box-sizing:border-box;">
                                Edit
                            </a>
                            <form action="{{ url('/admin/group/delete/' . $group->id) }}" method="POST"
                                onsubmit="return confirm('Delete this group?')" style="display:inline;margin:0;padding:0;">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    style="display:inline-flex;align-items:center;justify-content:center;width:62px;height:28px;font-size:12px;font-weight:500;border-radius:4px;border:1px solid #ef4444;color:#dc2626;background:#fef2f2;box-sizing:border-box;cursor:pointer;line-height:1;appearance:none;-webkit-appearance:none;">
                                    Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($groups->hasPages())
    <div class="mt-4">
        {{ $groups->links() }}
    </div>
    @endif
    @endif

</div>
@endsection