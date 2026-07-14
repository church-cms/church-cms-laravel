@extends('layouts.admin.layout')

@section('content')
@php
$dayNames = [0=>'Sun',1=>'Mon',2=>'Tue',3=>'Wed',4=>'Thu',5=>'Fri',6=>'Sat'];
$termLabels = ['day'=>'day(s)','week'=>'week(s)','month'=>'month(s)','year'=>'year(s)'];

$typeBadge = [
'public' => 'bg-green-100 text-green-700',
'private' => 'bg-gray-100 text-gray-600',
'online' => 'bg-blue-100 text-blue-700',
];

$activeTab = request('tab', 'description');
$isAdmin = auth()->user()->usergroup_id == 3;
@endphp

{{-- ── Page header ───────────────────────────────────────────────────── --}}
<div class="flex items-center justify-between mb-5">
    <h1 class="admin-h1 flex items-center gap-3">
        <a href="{{ url('/admin/events') }}"
            class="rounded-full bg-gray-100 hover:bg-gray-200 p-2 transition">
            <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
        </a>
        Event Details
    </h1>
    <div class="flex items-center gap-2">
        @if($isAdmin || Auth::user()->hasPermission('create-events'))
        <a href="{{ route('admin.events.editForm', $event->id) }}"
            class="text-sm px-3 py-1.5 rounded flex items-center gap-1.5 btn btn-primary submit-btn">
            <i class="fas fa-pen text-xs"></i> Edit
        </a>
        @endif
        @if($isAdmin || Auth::user()->hasPermission('delete-events'))
        <form action="{{ url('/admin/events/delete/' . $event->id) }}" method="POST"
            onsubmit="return confirm('Delete this event?')">
            @csrf @method('DELETE')
            <button type="submit"
                class="text-sm px-3 py-1.5 rounded flex items-center gap-1.5 bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 transition">
                <i class="fas fa-trash text-xs"></i> Delete
            </button>
        </form>
        @endif
    </div>
</div>

@include('partials.message')

{{-- ── Event hero card ───────────────────────────────────────────────── --}}
<div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-5 overflow-hidden">
    <div class="flex flex-col md:flex-row">

        {{-- Cover image --}}
        <div class="md:w-56 flex-shrink-0 bg-gray-50 flex items-center justify-center p-4">
            @if($event->ImagePath)
            <img src="{{ $event->ImagePath }}" class="w-full h-40 object-cover rounded">
            @else
            <div class="w-full h-40 bg-gray-100 rounded flex items-center justify-center">
                <i class="fas fa-calendar-alt text-4xl text-gray-300"></i>
            </div>
            @endif
        </div>

        {{-- Meta --}}
        <div class="flex-1 px-6 py-5">
            <div class="flex flex-wrap items-start gap-2 mb-2">
                <h2 class="text-xl font-bold text-gray-800 flex-1">{{ $event->title }}</h2>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full capitalize {{ $typeBadge[$event->select_type] ?? 'bg-gray-100 text-gray-500' }}">
                    {{ $event->select_type }}
                </span>
                @if($event->repeats == 1)
                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-purple-100 text-purple-700">
                    Recurring
                </span>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4 text-sm">

                <div class="flex items-start gap-2 text-gray-600">
                    <i class="fas fa-calendar-day text-gray-400 mt-0.5 w-4"></i>
                    <div>
                        <p class="font-medium text-gray-700 text-xs uppercase tracking-wide mb-0.5">
                            {{ $event->repeats == 1 ? 'Series Start' : 'Date & Time' }}
                        </p>
                        <p>{{ date('d M Y, h:i A', strtotime($event->start_date)) }}</p>
                        @if($event->repeats != 1)
                        <p class="text-gray-400 text-xs">ends {{ date('d M Y, h:i A', strtotime($event->end_date)) }}</p>
                        @else
                        <p class="text-gray-400 text-xs">series ends {{ date('d M Y', strtotime($event->end_date)) }}</p>
                        @endif
                    </div>
                </div>

                @if($event->repeats == 1)
                <div class="flex items-start gap-2 text-gray-600">
                    <i class="fas fa-rotate text-gray-400 mt-0.5 w-4"></i>
                    <div>
                        <p class="font-medium text-gray-700 text-xs uppercase tracking-wide mb-0.5">Recurrence</p>
                        <p>Every {{ $event->freq }} {{ $termLabels[$event->freq_term] ?? $event->freq_term }}</p>
                        @if($event->freq_term === 'week' && !empty($event->days_of_week))
                        <p class="text-gray-400 text-xs">
                            on {{ implode(', ', array_map(fn($d) => $dayNames[(int)$d] ?? '', $event->days_of_week)) }}
                        </p>
                        @endif
                    </div>
                </div>
                @endif

                <div class="flex items-start gap-2 text-gray-600">
                    <i class="fas fa-location-dot text-gray-400 mt-0.5 w-4"></i>
                    <div>
                        <p class="font-medium text-gray-700 text-xs uppercase tracking-wide mb-0.5">Location</p>
                        <p>{{ $event->location ?: '—' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-2 text-gray-600">
                    <i class="fas fa-tag text-gray-400 mt-0.5 w-4"></i>
                    <div>
                        <p class="font-medium text-gray-700 text-xs uppercase tracking-wide mb-0.5">Category</p>
                        <p class="capitalize">{{ $event->category ?: '—' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-2 text-gray-600">
                    <i class="fas fa-microphone text-gray-400 mt-0.5 w-4"></i>
                    <div>
                        <p class="font-medium text-gray-700 text-xs uppercase tracking-wide mb-0.5">Organised By</p>
                        <p>{{ $event->organised_by ?: '—' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-2 text-gray-600">
                    <i class="fas fa-circle-info text-gray-400 mt-0.5 w-4"></i>
                    <div>
                        <p class="font-medium text-gray-700 text-xs uppercase tracking-wide mb-0.5">Options</p>
                        <div class="flex flex-wrap gap-2 mt-0.5">
                            @if($event->publish_to_web)
                            <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">Published</span>
                            @endif
                            @if($event->enable_gallery)
                            <span class="text-xs bg-green-50 text-green-600 px-2 py-0.5 rounded-full">Gallery On</span>
                            @endif
                            @if($event->enable_attendance)
                            <span class="text-xs bg-orange-50 text-orange-600 px-2 py-0.5 rounded-full">Attendance On</span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ── Tabs ───────────────────────────────────────────────────────────── --}}
<div class="bg-white border border-gray-200 rounded-lg shadow-sm">

    {{-- Tab nav --}}
    <div class="flex border-b border-gray-200 overflow-x-auto">
        <button class="ev-tab-btn px-5 py-3 text-sm font-medium whitespace-nowrap transition border-b-2"
            data-tab="description">
            <i class="fas fa-align-left mr-1.5 text-xs"></i> Description
        </button>
        @if($event->enable_gallery)
        <button class="ev-tab-btn px-5 py-3 text-sm font-medium whitespace-nowrap transition border-b-2"
            data-tab="photos">
            <i class="fas fa-images mr-1.5 text-xs"></i>
            Photos
            @if($photos->count())
            <span class="ml-1 text-xs bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded-full">{{ $photos->count() }}</span>
            @endif
        </button>
        @endif
        @if($expired)
        <button class="ev-tab-btn px-5 py-3 text-sm font-medium whitespace-nowrap transition border-b-2"
            data-tab="attendees">
            <i class="fas fa-users mr-1.5 text-xs"></i>
            Attendees
            @if($attended->count())
            <span class="ml-1 text-xs bg-green-100 text-green-600 px-1.5 py-0.5 rounded-full">{{ $attended->count() }}</span>
            @endif
        </button>
        @endif
        @if($event->enable_attendance)
        <button class="ev-tab-btn px-5 py-3 text-sm font-medium whitespace-nowrap transition border-b-2"
            data-tab="attendance">
            <i class="fas fa-clipboard-check mr-1.5 text-xs"></i>
            Attendance
            @if($sessions->count())
            <span class="ml-1 text-xs bg-orange-100 text-orange-600 px-1.5 py-0.5 rounded-full">{{ $sessions->count() }}</span>
            @endif
        </button>
        @endif
        <button class="ev-tab-btn px-5 py-3 text-sm font-medium whitespace-nowrap transition border-b-2"
            data-tab="notes">
            <i class="fas fa-sticky-note mr-1.5 text-xs"></i>
            Notes
            @if($notes->count())
            <span class="ml-1 text-xs bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded-full">{{ $notes->count() }}</span>
            @endif
        </button>
    </div>

    {{-- ── Description tab ──────────────────────────────────────────── --}}
    <div class="ev-tab-panel px-6 py-5" data-tab="description">
        @if($event->description)
        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $event->description }}</p>
        @else
        <p class="text-sm text-gray-400 italic">No description added.</p>
        @endif
    </div>

    {{-- ── Photos tab ────────────────────────────────────────────────── --}}
    @if($event->enable_gallery)
    <div class="ev-tab-panel px-6 py-5" data-tab="photos">

        {{-- Upload form --}}
        <form action="{{ url('/admin/upload/photos/' . $event->id) }}" method="POST"
            enctype="multipart/form-data" class="mb-6">
            @csrf
            <label class="tw-form-label block mb-2">Upload Photos</label>
            <div class="flex items-center gap-3">
                <input type="file" name="photos[]" multiple accept="image/*"
                    class="text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0
                              file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700
                              hover:file:bg-blue-100 transition">
                <button type="submit"
                    class="text-sm px-3 py-1.5 rounded btn btn-primary submit-btn flex items-center gap-1.5">
                    <i class="fas fa-upload text-xs"></i> Upload
                </button>
            </div>
        </form>

        {{-- Photo grid --}}
        @if($photos->isEmpty())
        <p class="text-sm text-gray-400 italic">No photos uploaded yet.</p>
        @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
            @foreach($photos as $photo)
            <div class="relative group rounded overflow-hidden border border-gray-200">
                <a href="{{ $photo->FullPath }}" target="_blank">
                    <img src="{{ $photo->FullPath }}"
                        class="w-full h-28 object-cover transition group-hover:opacity-80">
                </a>
                <form action="{{ url('/admin/event/photo/delete/' . $photo->id) }}" method="POST"
                    class="absolute top-1 right-1 hidden group-hover:block"
                    onsubmit="return confirm('Delete this photo?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs leading-none shadow">
                        &times;
                    </button>
                </form>
                <p class="text-xs text-gray-400 px-1 py-0.5 truncate">{{ $photo->updated_at->format('d M Y') }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @endif

    {{-- ── Attendance tab ─────────────────────────────────────────────── --}}
    @if($event->enable_attendance)
    <div class="ev-tab-panel px-6 py-5" data-tab="attendance">

        {{-- Open / start a session --}}
        @can('create-attendance')
        @if($event->repeats == 1)
        {{-- Recurring: date picker pre-filled with next occurrence --}}
        @php
        $existingDates = $sessions->pluck('attendance_date')
        ->map(fn($d) => \Carbon\Carbon::parse($d)->toDateString())->toArray();
        $upcomingOcc = $event->upcomingOccurrences($existingDates, 5);
        $nextOcc = $upcomingOcc[0] ?? null;
        @endphp
        @if($nextOcc)
        <div class="mb-5 p-4 rounded-lg border border-gray-100 bg-gray-50">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Open Attendance Session</p>
            {{-- Quick-select occurrence pills --}}
            @if(count($upcomingOcc) > 1)
            <div class="flex flex-wrap gap-2 mb-3" id="occ-pills">
                @foreach($upcomingOcc as $i => $occ)
                <button type="button"
                    class="occ-pill text-sm px-3 py-1.5 rounded-lg border transition"
                    data-date="{{ $occ->toDateString() }}">
                    {{ $occ->format('D, d M') }}
                </button>
                @endforeach
            </div>
            @endif
            <form action="{{ route('admin.attendance.open', $event->id) }}" method="POST"
                class="flex flex-wrap items-center gap-3">
                @csrf
                <input type="date" name="attendance_date" id="occ-date-input"
                    value="{{ $nextOcc->toDateString() }}"
                    class="tw-form-control text-sm">
                <button type="submit"
                    class="text-sm px-4 py-2 rounded btn btn-primary submit-btn flex items-center gap-2">
                    <i class="fas fa-plus text-xs"></i> Open Session
                </button>
            </form>
        </div>
        @else
        <p class="text-sm text-gray-400 italic mb-5">No upcoming occurrences — series may have ended.</p>
        @endif
        @else
        {{-- Non-recurring: simple "open for today" --}}
        <a href="{{ url('admin/event/'.$event->id.'/managers') }}">
            <button type="button"
                class="text-sm px-4 py-2 rounded btn btn-primary submit-btn flex items-center gap-2">
                <i class="fas fa-plus text-xs"></i> Set Attendance Manager
            </button>
        </a>

        @php $todaySession = $sessions->firstWhere(fn($s) => \Carbon\Carbon::parse($s->attendance_date)->toDateString() === now()->toDateString()); @endphp
        @if(!$todaySession)
        <form action="{{ route('admin.attendance.open', $event->id) }}" method="POST" class="mb-5">
            @csrf
            <input type="hidden" name="attendance_date" value="{{ now()->toDateString() }}">
            <button type="submit"
                class="text-sm px-4 py-2 rounded btn btn-primary submit-btn flex items-center gap-2">
                <i class="fas fa-plus text-xs"></i> Open Session for Today
            </button>
        </form>
        @else
        <div class="mb-5 flex items-center gap-3">
            <span class="text-sm text-green-700 bg-green-50 border border-green-200 px-3 py-1.5 rounded-lg flex items-center gap-2">
                <i class="fas fa-circle text-green-400 text-xs"></i> Session open for today
            </span>
            <a href="{{ route('admin.attendance.checkin', $todaySession->id) }}"
                class="text-sm px-4 py-1.5 rounded bg-blue-600 text-white hover:bg-blue-700 transition flex items-center gap-1.5">
                <i class="fas fa-mobile-alt text-xs"></i> Start Check-in
            </a>
        </div>
        @endif
        @endif
        @endcan

        {{-- Sessions list --}}
        @if($sessions->isEmpty())
        <p class="text-sm text-gray-400 italic">No attendance sessions yet.</p>
        @else
        <div class="space-y-2">
            @foreach($sessions as $sess)
            @php $isLocked = (bool) $sess->locked_at; @endphp
            <div class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:bg-gray-50 transition">
                {{-- Date --}}
                <div class="flex-shrink-0 w-12 text-center bg-gray-100 rounded-lg py-1.5">
                    <p class="text-xs font-bold text-gray-700 uppercase leading-none">
                        {{ \Carbon\Carbon::parse($sess->attendance_date)->format('M') }}
                    </p>
                    <p class="text-lg font-bold text-gray-800 leading-tight">
                        {{ \Carbon\Carbon::parse($sess->attendance_date)->format('d') }}
                    </p>
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-sm font-medium text-gray-700">
                            {{ \Carbon\Carbon::parse($sess->attendance_date)->format('D, d M Y') }}
                        </span>
                        @if($isLocked)
                        <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">Locked</span>
                        @else
                        <span class="text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded-full">Open</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $sess->attendees_count }} {{ Str::plural('member', $sess->attendees_count) }} checked in
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    @if(!$isLocked)
                    @can('create-attendance')
                    <a href="{{ route('admin.attendance.checkin', $sess->id) }}"
                        class="text-xs px-2.5 py-1.5 rounded bg-blue-600 text-white hover:bg-blue-700 transition flex items-center gap-1">
                        <i class="fas fa-mobile-alt text-xs"></i> Check-in
                    </a>
                    @endcan
                    @endif
                    <a href="{{ route('admin.attendance.session', $sess->id) }}"
                        class="text-xs px-2.5 py-1.5 rounded border border-gray-200 text-gray-600 hover:bg-gray-100 transition">
                        View
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
    @endif

    {{-- ── Notes tab ─────────────────────────────────────────────────── --}}
    <div class="ev-tab-panel px-6 py-5" data-tab="notes">

        {{-- Add note form --}}
        <form id="note-form" class="mb-5">
            @csrf
            <input type="hidden" name="entity_id" value="{{ $event->id }}">
            <input type="hidden" name="entity_name" value="event">
            <input type="hidden" name="church_id" value="{{ $event->church_id }}">
            <input type="hidden" name="id" value="">
            <label class="tw-form-label block mb-1">Add Note</label>
            <textarea name="notes" id="note-input" rows="3" placeholder="Write a note…"
                class="tw-form-control w-full mb-2"></textarea>
            <button type="submit"
                class="text-sm px-3 py-1.5 rounded btn btn-primary submit-btn flex items-center gap-1.5">
                <i class="fas fa-paper-plane text-xs"></i> Save Note
            </button>
        </form>

        {{-- Notes list --}}
        <div id="notes-list">
            @forelse($notes as $note)
            <div class="note-item flex items-start justify-between gap-3 py-3 border-b border-gray-100 last:border-0"
                data-id="{{ $note->id }}">
                <div>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $note->notes }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $note->created_at->format('d M Y, h:i A') }}</p>
                </div>
                <button class="note-delete-btn flex-shrink-0 text-gray-300 hover:text-red-500 transition text-lg leading-none"
                    data-id="{{ $note->id }}" title="Delete note">&times;</button>
            </div>
            @empty
            <p class="text-sm text-gray-400 italic" id="notes-empty">No notes yet.</p>
            @endforelse
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
    (function() {
        var ACTIVE_TAB = 'border-blue-600 text-blue-600';
        var INACTIVE_TAB = 'border-transparent text-gray-500 hover:text-gray-700';
        var ACTIVE_SUB = 'bg-blue-600 text-white border-blue-600';
        var INACTIVE_SUB = 'bg-white text-gray-600 border-gray-200 hover:border-gray-300';

        // ── Tab switching ────────────────────────────────────────────────
        var tabBtns = document.querySelectorAll('.ev-tab-btn');
        var tabPanels = document.querySelectorAll('.ev-tab-panel');

        function activateTab(name) {
            tabBtns.forEach(function(btn) {
                var isActive = btn.dataset.tab === name;
                btn.className = btn.className
                    .replace(/border-blue-600|text-blue-600|border-transparent|text-gray-500|hover:text-gray-700/g, '').trim();
                btn.classList.add(...(isActive ? ACTIVE_TAB : INACTIVE_TAB).split(' '));
            });
            tabPanels.forEach(function(panel) {
                panel.classList.toggle('hidden', panel.dataset.tab !== name);
            });
        }

        tabBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                activateTab(btn.dataset.tab);
            });
        });
        activateTab('{{ $activeTab }}');

        // ── Occurrence pills ─────────────────────────────────────────────
        var pills = document.querySelectorAll('.occ-pill');
        var dateInput = document.getElementById('occ-date-input');
        var PILL_ON = ['border-blue-600', 'text-blue-600', 'bg-blue-50'];
        var PILL_OFF = ['border-gray-200', 'text-gray-700', 'bg-white'];

        function activatePill(btn) {
            pills.forEach(function(p) {
                p.classList.remove(...PILL_ON);
                p.classList.add(...PILL_OFF);
            });
            btn.classList.remove(...PILL_OFF);
            btn.classList.add(...PILL_ON);
        }

        if (dateInput && pills.length) {
            // Initialise: highlight pill matching the default date value
            pills.forEach(function(p) {
                if (p.dataset.date === dateInput.value) activatePill(p);
                p.addEventListener('click', function() {
                    dateInput.value = p.dataset.date;
                    activatePill(p);
                });
            });
            // Keep pills in sync when user edits the date field manually
            dateInput.addEventListener('change', function() {
                var match = false;
                pills.forEach(function(p) {
                    if (p.dataset.date === dateInput.value) {
                        activatePill(p);
                        match = true;
                    }
                });
                if (!match) {
                    pills.forEach(function(p) {
                        p.classList.remove(...PILL_ON);
                        p.classList.add(...PILL_OFF);
                    });
                }
            });
        }

        // ── Attendee sub-tabs ────────────────────────────────────────────
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

        if (subBtns.length) activateSub('attended');
        subBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                activateSub(btn.dataset.att);
            });
        });

        // ── Notes AJAX ───────────────────────────────────────────────────
        var noteForm = document.getElementById('note-form');
        var noteInput = document.getElementById('note-input');
        var notesList = document.getElementById('notes-list');
        var emptyMsg = document.getElementById('notes-empty');

        if (noteForm) {
            noteForm.addEventListener('submit', function(e) {
                e.preventDefault();
                var fd = new FormData(noteForm);
                fetch('{{ url(' / admin / notes ') }}', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': fd.get('_token')
                        },
                        body: fd
                    })
                    .then(function(r) {
                        return r.json();
                    })
                    .then(function(res) {
                        if (!res.message) return;
                        if (emptyMsg) emptyMsg.remove();
                        noteInput.value = '';
                        // Reload page to show persisted note with correct timestamp
                        window.location.reload();
                    });
            });
        }

        if (notesList) {
            notesList.addEventListener('click', function(e) {
                var btn = e.target.closest('.note-delete-btn');
                if (!btn) return;
                if (!confirm('Delete this note?')) return;
                var id = btn.dataset.id;
                var csrf = document.querySelector('meta[name="csrf-token"]');
                fetch('{{ url(' / admin / notes / delete ') }}/' + id, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrf ? csrf.content : ''
                        }
                    })
                    .then(function() {
                        var item = notesList.querySelector('.note-item[data-id="' + id + '"]');
                        if (item) item.remove();
                        if (!notesList.querySelector('.note-item')) {
                            notesList.innerHTML = '<p class="text-sm text-gray-400 italic">No notes yet.</p>';
                        }
                    });
            });
        }
    })();
</script>
@endpush