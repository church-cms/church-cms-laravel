@extends('layouts.admin.layout')

@section('content')
@php
$typeBadge = [
'public' => 'bg-green-100 text-green-700',
'private' => 'bg-gray-100 text-gray-600',
'online' => 'bg-blue-100 text-blue-700',
];

$isAdmin = auth()->user()->usergroup_id == 3;
@endphp


{{-- Page header --}}
<div class="flex items-center justify-between my-3">
    <h1 class="admin-h1">Events ({{ $count }})</h1>
    @if($isAdmin || Auth::user()->hasPermission('create-events'))
    <a href="{{ route('admin.events.new') }}"
        class="text-sm rounded px-3 py-1.5 flex items-center gap-2 btn btn-primary submit-btn">
        <i class="fas fa-plus text-xs"></i>
        <span>Create Event</span>
    </a>
    @endif
</div>

@include('partials.message')

<div class="py-5 bg-white shadow px-3">

    {{-- View toggle --}}
    <div class="flex items-center gap-2 mb-4">
        <button id="view-table-btn"
            class="text-sm px-3 py-1.5 rounded flex items-center gap-1.5 transition bg-blue-600 text-white">
            <i class="fas fa-list text-xs"></i>
            <span>Table</span>
        </button>
        <button id="view-calendar-btn"
            class="text-sm px-3 py-1.5 rounded flex items-center gap-1.5 transition bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fas fa-calendar-alt text-xs"></i>
            <span>Calendar</span>
        </button>
    </div>

    {{-- ── Table view ──────────────────────────────────────────────────── --}}
    <div id="view-table">

        {{-- Filter row --}}
        <div class="flex flex-wrap items-center gap-2 mb-4 border-b border-gray-100 pb-3">
            @foreach(['all' => 'All Events', 'upcoming' => 'Upcoming', 'completed' => 'Completed'] as $key => $label)
            <a href="{{ request()->fullUrlWithQuery(['filter' => $key, 'page' => 1]) }}"
                class="text-sm px-3 py-1.5 rounded-full border transition
                      {{ $filter === $key
                            ? 'border-blue-600 bg-blue-600 text-white'
                            : 'border-gray-200 text-gray-600 hover:border-gray-300 hover:bg-gray-50' }}">
                {{ $label }}
            </a>
            @endforeach

            {{-- Category filter --}}
            <div class="ml-auto">
                <select id="category-filter"
                    class="text-sm border border-gray-200 rounded-full px-3 py-1.5 text-gray-600 bg-white focus:outline-none focus:border-blue-400 transition cursor-pointer">
                    <option value="">All Categories</option>
                    @foreach(['Culturals' => 'Culturals', 'Education' => 'Education', 'Meeting' => 'Meeting', 'prayer' => 'Prayer', 'sermon' => 'Sermon'] as $val => $lbl)
                    <option value="{{ $val }}" {{ $category === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($events->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <i class="fas fa-calendar-times text-4xl mb-3 block"></i>
            <p class="text-sm">No events yet.</p>
        </div>
        @else
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    <th class="px-5 py-3 text-left">Title</th>
                    <th class="px-5 py-3 text-left">Date</th>
                    <th class="px-5 py-3 text-left">Category</th>
                    <th class="px-5 py-3 text-left">Location</th>
                    <th class="px-5 py-3 text-left">Type</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($events as $event)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4">
                        <p class="font-medium text-gray-800">{{ $event->title }}</p>
                        @if($event->organised_by)
                        <p class="text-xs text-gray-400 mt-0.5">{{ $event->organised_by }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-gray-600 whitespace-nowrap">
                        <p>{{ date('d M Y', strtotime($event->start_date)) }}</p>
                        <p class="text-xs text-gray-400">{{ date('h:i A', strtotime($event->start_date)) }}</p>
                    </td>
                    <td class="px-5 py-4 text-gray-600 capitalize">{{ $event->category ?: '—' }}</td>
                    <td class="px-5 py-4 text-gray-500 max-w-xs truncate">{{ $event->location ?: '—' }}</td>
                    <td class="px-5 py-4">
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full capitalize {{ $typeBadge[$event->select_type] ?? 'bg-gray-100 text-gray-500' }}">
                            {{ $event->select_type ?: '—' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <button onclick="window.openEventPopup({{ $event->id }})"
                                class="text-xs text-blue-600 hover:text-blue-800 font-medium transition">
                                View
                            </button>
                            @if($isAdmin || Auth::user()->hasPermission('create-events'))
                            <a href="{{ route('admin.events.editForm', $event->id) }}"
                                class="text-xs text-gray-500 hover:text-gray-700 font-medium transition">
                                Edit
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($events->hasPages())
        <div class="px-5 py-3 border-t border-gray-100">
            {{ $events->links() }}
        </div>
        @endif
        @endif
    </div>

    {{-- ── Calendar view ────────────────────────────────────────────────── --}}
    <div id="view-calendar" class="hidden">
        <div id="event-calendar"></div>
    </div>

</div>

{{-- ── Event quick-view popup ──────────────────────────────────────────── --}}
<div id="ev-popup-overlay" class="hidden fixed inset-0 z-50" style="background:rgba(51,49,49,0.41)">
    <div class="flex items-center justify-center h-full px-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm relative overflow-hidden">

            <button id="ev-popup-close"
                class="absolute top-2 right-2 z-10 w-7 h-7 flex items-center justify-center rounded-full bg-white bg-opacity-80 text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition text-lg leading-none">
                &times;
            </button>

            <div id="ev-popup-cover-wrap" class="hidden bg-gray-100 overflow-hidden" style="max-height:55vh">
                <img id="ev-popup-cover" src="" alt="" class="w-full h-full object-cover">
            </div>

            <div class="px-5 py-4">
                <div class="flex items-start gap-2 mb-3">
                    <h3 id="ev-popup-title" class="text-base font-semibold text-gray-800 flex-1 leading-snug"></h3>
                    <span id="ev-popup-badge" class="text-xs font-medium px-2 py-0.5 rounded-full capitalize flex-shrink-0"></span>
                </div>

                <p id="ev-popup-desc" class="text-sm text-gray-600 mb-4 leading-relaxed line-clamp-3"></p>

                <div class="space-y-1.5 text-sm text-gray-600 mb-5">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-calendar-day text-gray-400 mt-0.5 w-4 flex-shrink-0"></i>
                        <span id="ev-popup-dates"></span>
                    </div>
                    <div id="ev-popup-location-row" class="flex items-start gap-2">
                        <i class="fas fa-location-dot text-gray-400 mt-0.5 w-4 flex-shrink-0"></i>
                        <span id="ev-popup-location"></span>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <a id="ev-popup-detail-link" href="#"
                        class="text-sm px-3 py-1.5 rounded btn btn-primary submit-btn flex items-center gap-1.5">
                        <i class="fas fa-eye text-xs"></i> View Details
                    </a>
                    <button id="ev-popup-close2"
                        class="text-sm px-3 py-1.5 rounded border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
                        Close
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    (function() {
        // ── Category filter ──────────────────────────────────────────────────
        var catFilter = document.getElementById('category-filter');
        if (catFilter) {
            catFilter.addEventListener('change', function() {
                var url = new URL(window.location.href);
                if (catFilter.value) {
                    url.searchParams.set('category', catFilter.value);
                } else {
                    url.searchParams.delete('category');
                }
                url.searchParams.set('page', '1');
                window.location.href = url.toString();
            });
        }

        // ── View toggle ──────────────────────────────────────────────────────
        var tableBtn = document.getElementById('view-table-btn');
        var calBtn = document.getElementById('view-calendar-btn');
        var tablePanel = document.getElementById('view-table');
        var calPanel = document.getElementById('view-calendar');
        var calInited = false;

        var ACTIVE = 'bg-blue-600 text-white';
        var INACTIVE = 'bg-gray-100 text-gray-600 hover:bg-gray-200';

        function setActive(btn) {
            [tableBtn, calBtn].forEach(function(b) {
                b.className = b.className
                    .replace(/bg-blue-600|text-white|bg-gray-100|text-gray-600|hover:bg-gray-200/g, '').trim();
                b.classList.add(...(b === btn ? ACTIVE : INACTIVE).split(' '));
            });
        }

        tableBtn.addEventListener('click', function() {
            setActive(tableBtn);
            tablePanel.classList.remove('hidden');
            calPanel.classList.add('hidden');
        });

        calBtn.addEventListener('click', function() {
            setActive(calBtn);
            tablePanel.classList.add('hidden');
            calPanel.classList.remove('hidden');
            if (!calInited) initCalendar();
        });


        function initCalendar() {

            // Prevent duplicate initialization
            if (calInited && cal) {
                return;
            }

            var fc = window.FullCalendarLib;

            if (!fc) {
                console.error('FullCalendar library not loaded');
                return;
            }

            var calendarEl = document.getElementById('event-calendar');

            if (!calendarEl) {
                console.error('Calendar element not found');
                return;
            }

            cal = new fc.Calendar(calendarEl, {

                plugins: [
                    fc.dayGridPlugin,
                    fc.timeGridPlugin,
                    fc.interactionPlugin
                ],

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },

                initialView: 'dayGridMonth',

                dayMaxEvents: true,

                height: 'auto',

                nowIndicator: true,

                editable: false,

                selectable: false,

                events: {
                    url: '{{ url("/admin/events/show") }}',
                    method: 'GET',
                    failure: function() {
                        console.log('Event loading failed');
                    }
                },

                loading: function(isLoading) {

                    let loader = document.getElementById('calendar-loader');

                    if (!loader) return;

                    if (isLoading) {
                        loader.classList.remove('hidden');
                    } else {
                        loader.classList.add('hidden');
                    }
                },

                eventClick: function(info) {

                    info.jsEvent.preventDefault();

                    if (window.openEventPopup) {
                        window.openEventPopup(info.event.id);
                    }
                },

                eventDidMount: function(info) {

                    if (info.event.start) {

                        info.el.setAttribute(
                            'title',
                            info.event.title +
                            ' - ' +
                            info.event.start.toLocaleString()
                        );
                    }
                }

            });

            cal.render();

            calInited = true;
        }

        // ── Event popup ──────────────────────────────────────────────────────
        var overlay = document.getElementById('ev-popup-overlay');
        var closeBtn = document.getElementById('ev-popup-close');
        var closeBtn2 = document.getElementById('ev-popup-close2');
        var coverWrap = document.getElementById('ev-popup-cover-wrap');
        var coverImg = document.getElementById('ev-popup-cover');
        var titleEl = document.getElementById('ev-popup-title');
        var badgeEl = document.getElementById('ev-popup-badge');
        var descEl = document.getElementById('ev-popup-desc');
        var datesEl = document.getElementById('ev-popup-dates');
        var locRow = document.getElementById('ev-popup-location-row');
        var locEl = document.getElementById('ev-popup-location');
        var detailLink = document.getElementById('ev-popup-detail-link');

        var badgeMap = {
            'public': 'bg-green-100 text-green-700',
            'private': 'bg-gray-100 text-gray-600',
            'online': 'bg-blue-100 text-blue-700',
        };

        function closePopup() {
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        window.openEventPopup = function(id) {
        
            fetch('{{ url("admin/events/showdetails") }}/' + id, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                .then(function(r) {
                    return r.json();
                })
                .then(function(res) {
                    var ev = res.data && res.data[0];
                    if (!ev) return;

                    titleEl.textContent = ev.title || '';
                    descEl.textContent = ev.description || '';

                    var cls = badgeMap[ev.select_type] || 'bg-gray-100 text-gray-500';
                    badgeEl.className = 'text-xs font-medium px-2 py-0.5 rounded-full capitalize flex-shrink-0 ' + cls;
                    badgeEl.textContent = ev.select_type || '';

                    datesEl.textContent = ev.start_date + (ev.end_date && ev.end_date !== ev.start_date ? ' – ' + ev.end_date : '');

                    if (ev.location) {
                        locEl.textContent = ev.location;
                        locRow.classList.remove('hidden');
                    } else {
                        locRow.classList.add('hidden');
                    }

                    if (ev.image) {
                        coverImg.src = ev.image;
                        coverWrap.classList.remove('hidden');
                    } else {
                        coverWrap.classList.add('hidden');
                        coverImg.src = '';
                    }

                    detailLink.href = '{{ url("admin/events/show/details") }}/' + ev.id;
                    overlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
        };

        closeBtn.addEventListener('click', closePopup);
        closeBtn2.addEventListener('click', closePopup);
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay || e.target === overlay.firstElementChild) closePopup();
        });
    })();
</script>
@endpush