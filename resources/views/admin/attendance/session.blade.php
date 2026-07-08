@extends('layouts.admin.layout')

@section('content')
<div class="py-5 bg-white shadow px-3">
    @include('partials.message')


    <h1 class="admin-h1 mb-1 flex items-center">
        <a href="{{ route('admin.attendance.sessions', $session->event_id) }}" class="rounded-full bg-gray-100 p-2 mr-2" title="Back">
            <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
        </a>
        {{ $session->event->title }}
        <span class="ml-3 text-base font-normal text-gray-500">
            {{ \Carbon\Carbon::parse($session->attendance_date)->format('D, d M Y') }}
        </span>
    </h1>

    {{-- Session status bar --}}
    <div class="flex flex-wrap gap-3 items-center my-4">
        <span class="text-sm font-semibold text-gray-700">
            {{ $attendees->count() }} checked in
        </span>
        @if($session->locked_at)
        <span class="bg-red-100 text-red-700 px-3 py-1 rounded text-sm">
            Locked at {{ \Carbon\Carbon::parse($session->locked_at)->format('d M H:i') }}
            by {{ optional($session->lockedBy)->name }}
        </span>
        @can('update-attendance')
        <form action="{{ route('admin.attendance.unlock', $session->id) }}" method="POST" class="inline">
            @csrf
            <button class="text-sm bg-yellow-100 text-yellow-700 px-3 py-1 rounded">Unlock Session</button>
        </form>
        @endcan
        @else
        <span class="bg-green-100 text-green-700 px-3 py-1 rounded text-sm">Session Open</span>
        @can('create-attendance')
        <a href="{{ route('admin.attendance.checkin', $session->id) }}"
            class="text-sm bg-blue-600 text-white px-4 py-1.5 rounded flex items-center gap-1.5 hover:bg-blue-700 transition">
            <i class="fas fa-mobile-alt text-xs"></i> Start Check-in
        </a>
        @endcan
        @can('update-attendance')
        <form action="{{ route('admin.attendance.lock', $session->id) }}" method="POST" class="inline"
            onsubmit="return confirm('Lock this session? No further check-ins will be allowed.')">
            @csrf
            <button class="text-sm bg-red-100 text-red-700 px-3 py-1 rounded">Lock Session</button>
        </form>
        @endcan
        @endif
        <a href="{{ route('admin.attendance.export', $session->id) }}"
            class="text-sm bg-gray-200 text-gray-700 px-3 py-1 rounded ml-auto">
            Export CSV
        </a>
    </div>

    {{-- Attendees table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Member</th>
                    <th class="px-4 py-2">Mobile</th>
                    <th class="px-4 py-2">Checked In At</th>
                    <th class="px-4 py-2">Scanned By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendees as $i => $attendee)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-400">{{ $i + 1 }}</td>
                    <td class="px-4 py-2 flex items-center gap-2">
                        @if($attendee->userprofile && $attendee->userprofile->avatar)
                        <img src="{{ \Storage::disk('public')->url($attendee->userprofile->avatar) }}"
                            class="w-7 h-7 rounded-full object-cover">
                        @else
                        <div class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center text-xs text-gray-500">
                            {{ strtoupper(substr($attendee->member->name, 0, 1)) }}
                        </div>
                        @endif
                        <div>
                            <p class="font-medium">{{ optional($attendee->userprofile)->firstname }} {{ optional($attendee->userprofile)->lastname }}</p>
                            <p class="text-xs text-gray-400">{{ $attendee->member->name }}</p>
                        </div>
                    </td>
                    <td class="px-4 py-2 text-gray-500">{{ $attendee->member->mobile_no }}</td>
                    <td class="px-4 py-2">
                        {{ $attendee->scanned_at ? \Carbon\Carbon::parse($attendee->scanned_at)->format('H:i:s') : '—' }}
                    </td>
                    <td class="px-4 py-2 text-gray-500">{{ optional($attendee->scannedBy)->name }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-400">No members checked in yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection