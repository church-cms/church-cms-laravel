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
        <span class="text-sm text-gray-500">
            of {{ $total_count }} total members
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
        <!-- <a href="{{ route('admin.attendance.export', $session->id) }}"
            class="text-sm bg-gray-200 text-gray-700 px-3 py-1 rounded ml-auto">
            Export CSV
        </a> -->
    </div>

    {{-- Attendee tabs --}}
    <div class="flex gap-3 mb-4">
        <button class="att-sub-btn text-sm px-3 py-1.5 rounded border transition" data-att="attendees">
            <i class="fas fa-check-circle mr-1 text-green-500"></i>
            Attendees <span class="text-xs text-gray-400">({{ $attendees->count() }})</span>
        </button>
        <button class="att-sub-btn text-sm px-3 py-1.5 rounded border transition" data-att="not_attendees">
            <i class="fas fa-times-circle mr-1 text-red-400"></i>
            Not Attendees <span class="text-xs text-gray-400">({{ $not_attendees->count() }})</span>
        </button>
    </div>

    {{-- Attendees table --}}
    <div class="att-sub-panel overflow-x-auto" data-att="attendees">
        <table class="w-full text-sm text-left table-fixed">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-2 w-12">#</th>
                    <th class="px-4 py-2 w-1/3">Member</th>
                    <th class="px-4 py-2 w-1/5">Mobile</th>
                    <th class="px-4 py-2 w-1/5">Checked In At</th>
                    <th class="px-4 py-2">Scanned By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendees as $i => $attendee)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-400">{{ $i + 1 }}</td>
                    <td class="px-4 py-2 flex items-center gap-2">
                        <a href="{{ url('/admin/member/show/'.$attendee->member->name) }}"
                            class="flex items-center gap-3 p-3 rounded border border-gray-100 hover:bg-gray-50 transition">
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
                        </a>
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

    {{-- Not attendees table --}}
    <div class="att-sub-panel overflow-x-auto hidden" data-att="not_attendees">
        <table class="w-full text-sm text-left table-fixed">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-2 w-12">#</th>
                    <th class="px-4 py-2 w-1/3">Member</th>
                    <th class="px-4 py-2 w-1/5">Mobile</th>
                    <th class="px-4 py-2 w-1/5">Checked In At</th>
                    <th class="px-4 py-2">Scanned By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($not_attendees as $i => $member)
                @php
                $mem=url('admin/member/show/'.$member['member_name']);
                @endphp
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-400">{{ $i + 1 }} </td>
                    <td class="px-4 py-2 flex items-center gap-2">
                        <a href="{{ $mem }}"
                            class="flex items-center gap-3 p-3 rounded border border-gray-100 hover:bg-gray-50 transition">
                            @if($member['avatar_url'])
                            <img src="{{ $member['avatar_url'] }}"
                                class="w-7 h-7 rounded-full object-cover">
                            @else
                            <div class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center text-xs text-gray-500">
                                {{ strtoupper(substr($member['member_name'], 0, 1)) }}
                            </div>
                            @endif

                            <div>

                                <p class="font-medium">{{ $member['member_name'] }}</p>

                            </div>
                        </a>
                    </td>
                    <td class="px-4 py-2 text-gray-500">{{ $member['mobile_no'] }}</td>
                    <td class="px-4 py-2 text-gray-400">—</td>
                    <td class="px-4 py-2 text-gray-400">—</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-400">All members have checked in.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
@push('scripts')
<script>
    (function() {
        var ACTIVE_SUB = 'bg-blue-600 text-white border-blue-600';
        var INACTIVE_SUB = 'bg-white text-gray-600 border-gray-200 hover:border-gray-300';

        var subBtns = document.querySelectorAll('.att-sub-btn');
        var subPanels = document.querySelectorAll('.att-sub-panel');

        function activateSub(name) {
            subBtns.forEach(function(btn) {
                var isActive = btn.dataset.att === name;
                btn.className = btn.className
                    .replace(/bg-blue-600|text-white|border-blue-600|bg-white|text-gray-600|border-gray-200|hover:border-gray-300/g, '').trim();
                btn.classList.add(...(isActive ? ACTIVE_SUB : INACTIVE_SUB).split(' '));
            });
            subPanels.forEach(function(panel) {
                panel.classList.toggle('hidden', panel.dataset.att !== name);
            });
        }

        if (subBtns.length) activateSub('attendees');
        subBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                activateSub(btn.dataset.att);
            });
        });
    })();
</script>
@endpush