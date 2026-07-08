@extends('layouts.admin.layout')

@section('content')
<div class="py-5 bg-white shadow px-3">
    @include('partials.message')

    <h1 class="admin-h1 mb-3 flex items-center">
        <a href="{{ url('/admin/events') }}" class="rounded-full bg-gray-100 p-2 mr-2" title="Back">
            <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
        </a>
        Attendance Sessions — {{ $event->title }}
    </h1>

    <div class="flex flex-wrap gap-2 mb-4 text-sm text-gray-600">
        <span class="bg-gray-100 px-2 py-1 rounded">{{ ucfirst($event->category) }}</span>
        <span class="bg-gray-100 px-2 py-1 rounded">{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}</span>
        @if($event->repeats === 'yes')
        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded">Recurring</span>
        @endif
    </div>

    {{-- Open new session --}}
    @can('create-attendance')
    <div class="bg-gray-50 border border-gray-200 rounded p-4 mb-6">
        <h2 class="font-semibold text-gray-700 mb-3">Open a New Session</h2>
        <form action="{{ route('admin.attendance.open', $event->id) }}" method="POST" class="flex flex-wrap gap-3 items-end">
            @csrf
            <div>
                <label class="block text-xs text-gray-500 mb-1">Date</label>
                <input type="date" name="attendance_date" value="{{ \Carbon\Carbon::today()->toDateString() }}"
                    class="border border-gray-300 rounded px-3 py-1 text-sm">
            </div>
            <button type="submit" class="blue-bg text-white text-sm px-4 py-1 rounded">
                Open Session
            </button>
        </form>
    </div>
    @endcan

    {{-- Sessions table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Opened By</th>
                    <th class="px-4 py-2">Attendees</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($session->attendance_date)->format('d M Y') }}</td>
                    <td class="px-4 py-2">{{ optional($session->openedBy)->name }}</td>
                    <td class="px-4 py-2 font-semibold">{{ $session->attendees_count }}</td>
                    <td class="px-4 py-2">
                        @if($session->locked_at)
                        <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs">
                            Locked {{ \Carbon\Carbon::parse($session->locked_at)->format('d M H:i') }}
                        </span>
                        @else
                        <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs">Open</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 flex gap-2 flex-wrap">
                        <a href="{{ route('admin.attendance.session', $session->id) }}"
                            class="text-xs blue-bg text-white px-2 py-1 rounded">View</a>
                        <a href="{{ route('admin.attendance.export', $session->id) }}"
                            class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded">Export CSV</a>
                        @can('update-attendance')
                        @if($session->locked_at)
                        <form action="{{ route('admin.attendance.unlock', $session->id) }}" method="POST" class="inline">
                            @csrf
                            <button class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded">Unlock</button>
                        </form>
                        @else
                        <form action="{{ route('admin.attendance.lock', $session->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Lock this session? No further check-ins will be allowed.')">
                            @csrf
                            <button class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded">Lock</button>
                        </form>
                        @endif
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-400">No sessions yet. Open one above.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Manage staff --}}
    @can('update-attendance')
    <div class="mt-6">
        <a href="{{ route('admin.event.managers', $event->id) }}"
            class="text-sm text-blue-600 underline">Manage Church Member Assignments →</a>
    </div>
    @endcan
</div>
@endsection