@extends('layouts.admin.layout')

@section('content')
<div class="py-5 bg-white shadow px-3">
    @include('partials.message')

    <h1 class="admin-h1 mb-3 flex items-center">
        <a href="{{ route('admin.attendance.sessions', $event->id) }}" class="rounded-full bg-gray-100 p-2 mr-2" title="Back">
            <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
        </a>
        Church Member Assignment — {{ $event->title }}
    </h1>

    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Assign new staff --}}
        @if($event->attendance_scope=='all')
        <div class="lg:w-1/3">
            <div class="bg-gray-50 border border-gray-200 rounded p-4">
                <h2 class="font-semibold text-gray-700 mb-3">Assign Church Member</h2>
                @if($subadmins->isEmpty())
                <p class="text-sm text-gray-400">All available Church Member are already assigned.</p>
                @else
                <form action="{{ route('admin.event.managers.store', $event->id) }}" method="POST">
                    @csrf
                    <select name="user_id" class="border border-gray-300 rounded px-3 py-2 text-sm w-full mb-3">
                        <option value="">Select church member…</option>
                        @foreach($subadmins as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="blue-bg text-white text-sm px-4 py-2 rounded w-full">
                        Assign
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endif

        {{-- Assigned staff list --}}
        <div class="lg:w-2/3">
            <h2 class="font-semibold text-gray-700 mb-3">Assigned Church Member ({{ $assigned->count() }})</h2>
            @forelse($assigned as $manager)
            <div class="flex items-center justify-between border-b py-2">
                <div class="flex items-center gap-3">
                    @if(optional($manager->staff->userprofile)->avatar)
                    <img src="{{ \Storage::disk('public')->url($manager->staff->userprofile->avatar) }}"
                        class="w-8 h-8 rounded-full object-cover">
                    @else
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs text-gray-500">
                        {{ strtoupper(substr($manager->staff->name, 0, 1)) }}
                    </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium">{{ $manager->staff->name }}</p>
                        <p class="text-xs text-gray-400">{{ $manager->staff->email }}</p>
                    </div>
                </div>
                <form action="{{ route('admin.event.managers.remove', [$event->id, $manager->user_id]) }}" method="POST"
                    onsubmit="return confirm('Remove this staff member?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-xs text-red-500 hover:underline">Remove</button>
                </form>
            </div>
            @empty
            <p class="text-sm text-gray-400 py-4">No staff assigned yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection