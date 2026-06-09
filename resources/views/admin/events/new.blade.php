@extends('layouts.admin.layout')

@section('content')
<div class="w-full max-w-3xl">

    <h1 class="admin-h1 mb-6 flex items-center gap-3">
        <a href="{{ url('/admin/events') }}" class="rounded-full bg-gray-100 hover:bg-gray-200 p-2 transition">
            <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
        </a>
        Create Event
    </h1>

    @include('partials.message')

    <form method="POST" action="{{ route('admin.events.storeNew') }}" id="event-create-form">
        @csrf

        {{-- ── Row 1: Event Type ────────────────────────────────────────────── --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-5">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Event Type <span class="text-red-500">*</span></h2>
            </div>
            <div class="px-6 py-5">
                <div class="flex gap-3 flex-wrap" id="event-type-group">
                    @foreach(['private' => ['label' => 'Private', 'icon' => 'fa-lock', 'desc' => 'Members only'],
                    'public' => ['label' => 'Public', 'icon' => 'fa-globe', 'desc' => 'Open to all'],
                    ] as $val => $opt)
                    @php $selType = old('select_type', 'public') === $val; @endphp
                    <label class="ev-pill flex-1 min-w-[140px] cursor-pointer border-2 rounded-lg px-4 py-3 flex items-center gap-3 transition select-none
                        {{ $selType ? 'border-blue-600 bg-blue-50' : 'border-gray-200 bg-white hover:border-blue-300 hover:bg-blue-50' }}">
                        <input type="radio" name="select_type" value="{{ $val }}" class="sr-only"
                            {{ $selType ? 'checked' : '' }}>
                        <i class="fas {{ $opt['icon'] }} w-4 {{ $selType ? 'text-blue-500' : 'text-gray-400' }}"></i>
                        <div>
                            <p class="text-sm font-semibold {{ $selType ? 'text-blue-700' : 'text-gray-800' }}">{{ $opt['label'] }}</p>
                            <p class="text-xs text-gray-400">{{ $opt['desc'] }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('select_type')<p class="tw-form-error text-red-500 mt-2">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- ── Row 2: Schedule Type ─────────────────────────────────────────── --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-5">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Schedule <span class="text-red-500">*</span></h2>
            </div>
            <div class="px-6 py-5">
                <div class="flex gap-3 flex-wrap" id="schedule-type-group">
                    @foreach(['0' => ['label' => 'One-Time Event', 'icon' => 'fa-calendar-check', 'desc' => 'Happens once'],
                    '1' => ['label' => 'Recurring Event', 'icon' => 'fa-rotate', 'desc' => 'Repeats on a schedule']] as $val => $opt)
                    @php $selSched = old('schedule', '0') === $val; @endphp
                    <label class="ev-pill flex-1 min-w-[180px] cursor-pointer border-2 rounded-lg px-4 py-3 flex items-center gap-3 transition select-none
                        {{ $selSched ? 'border-blue-600 bg-blue-50' : 'border-gray-200 bg-white hover:border-blue-300 hover:bg-blue-50' }}">
                        <input type="radio" name="schedule" value="{{ $val }}" class="sr-only"
                            {{ $selSched ? 'checked' : '' }}>
                        <i class="fas {{ $opt['icon'] }} w-4 {{ $selSched ? 'text-blue-500' : 'text-gray-400' }}"></i>
                        <div>
                            <p class="text-sm font-semibold {{ $selSched ? 'text-blue-700' : 'text-gray-800' }}">{{ $opt['label'] }}</p>
                            <p class="text-xs text-gray-400">{{ $opt['desc'] }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('schedule')<p class="tw-form-error text-red-500 mt-2">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- ── Row 3: Event Details ─────────────────────────────────────────── --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-5">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Event Details</h2>
            </div>
            <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="md:col-span-2">
                    <label class="tw-form-label">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="tw-form-control w-full @error('title') border-red-400 @enderror"
                        placeholder="e.g. Sunday Morning Service">
                    @error('title')<p class="tw-form-error text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="tw-form-label">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3"
                        class="tw-form-control w-full @error('description') border-red-400 @enderror"
                        placeholder="Brief description of the event">{{ old('description') }}</textarea>
                    @error('description')<p class="tw-form-error text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="tw-form-label">Location <span class="text-red-500">*</span></label>
                    <input type="text" name="location" value="{{ old('location') }}"
                        class="tw-form-control w-full @error('location') border-red-400 @enderror"
                        placeholder="Where is this event held?">
                    <p class="text-xs text-gray-400 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Common venues: Sanctuary · Foyer · Community Hall · Study Room
                    </p>
                    @error('location')<p class="tw-form-error text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Category <span class="text-red-500">*</span></label>
                    <select name="category" class="tw-form-control w-full @error('category') border-red-400 @enderror">
                        <option value="">Select category…</option>
                        @foreach($categories as $val => $label)
                        <option value="{{ $val }}" {{ old('category') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category')<p class="tw-form-error text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Organised By <span class="text-red-500">*</span></label>
                    <input type="text" name="organised_by" value="{{ old('organised_by') }}"
                        class="tw-form-control w-full @error('organised_by') border-red-400 @enderror"
                        placeholder="Person or group name">
                    @error('organised_by')<p class="tw-form-error text-red-500">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        {{-- ── Row 4: Date & Time ───────────────────────────────────────────── --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-5">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Date &amp; Time</h2>
            </div>
            <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-5">

                <div>
                    <label class="tw-form-label">Event Date <span class="text-red-500">*</span></label>
                    <input type="date" name="event_date" id="event_date"
                        value="{{ old('event_date', date('Y-m-d')) }}"
                        min="{{ date('Y-m-d') }}"
                        class="tw-form-control w-full @error('event_date') border-red-400 @enderror">
                    @error('event_date')<p class="tw-form-error text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Start Time <span class="text-red-500">*</span></label>
                    @php
                    $oldTime = old('start_time', '09:00');
                    @endphp
                    <select name="start_time" id="start_time"
                        class="tw-form-control w-full @error('start_time') border-red-400 @enderror">
                        @for($i = 0; $i < 96; $i++)
                            @php
                            $h24=intdiv($i * 15, 60);
                            $m=($i * 15) % 60;
                            $val=sprintf('%02d:%02d', $h24, $m);
                            $h12=$h24 % 12 ?: 12;
                            $ampm=$h24 < 12 ? 'AM' : 'PM' ;
                            @endphp
                            <option value="{{ $val }}" {{ $oldTime === $val ? 'selected' : '' }}>
                            {{ sprintf('%d:%02d %s', $h12, $m, $ampm) }}
                            </option>
                            @endfor
                    </select>
                    @error('start_time')<p class="tw-form-error text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Duration <span class="text-red-500">*</span></label>
                    <select name="duration" id="duration"
                        class="tw-form-control w-full @error('duration') border-red-400 @enderror">
                        <option value="">Select duration…</option>
                        @foreach([30 => '30 minutes', 60 => '1 hour', 90 => '1 hour 30 min',
                        120 => '2 hours', 180 => '3 hours', 240 => '4 hours', 480 => '8 hours'] as $mins => $label)
                        <option value="{{ $mins }}" {{ old('duration') == $mins ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1" id="end-time-preview"></p>
                    @error('duration')<p class="tw-form-error text-red-500">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        {{-- ── Row 5: Recurring Options (shown only when recurring) ─────────── --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-5 {{ old('schedule') === '1' ? '' : 'hidden' }}" id="recurring-section">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Recurring Settings</h2>
            </div>
            <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-5">

                <div>
                    <label class="tw-form-label">Repeat Every <span class="text-red-500">*</span></label>
                    <input type="number" name="freq" id="freq" min="1"
                        value="{{ old('freq', 1) }}"
                        class="tw-form-control w-full @error('freq') border-red-400 @enderror"
                        placeholder="1">
                    @error('freq')<p class="tw-form-error text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Period <span class="text-red-500">*</span></label>
                    <select name="freq_term" id="freq_term"
                        class="tw-form-control w-full @error('freq_term') border-red-400 @enderror">
                        <option value="">Select period…</option>
                        @foreach(['day' => 'Day(s)', 'week' => 'Week(s)', 'month' => 'Month(s)', 'year' => 'Year(s)'] as $val => $label)
                        <option value="{{ $val }}" {{ old('freq_term') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('freq_term')<p class="tw-form-error text-red-500">{{ $message }}</p>@enderror
                </div>



                <div id="month-type-row" class="hidden">
                    <label class="tw-form-label">Repeat on <span class="text-red-500">*</span></label>
                    <select name="month_type" id="month_type"
                        class="tw-form-control w-full @error('month_type') border-red-400 @enderror">
                        <option value="">Select option…</option>
                    </select>
                    @error('month_type')<p class="tw-form-error text-red-500">{{ $message }}</p>@enderror
                </div>



                <div>
                    <label class="tw-form-label">Series Ends On <span class="text-red-500">*</span></label>
                    <input type="date" name="series_end_date" id="series_end_date"
                        value="{{ old('series_end_date') }}"
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        class="tw-form-control w-full @error('series_end_date') border-red-400 @enderror">
                    @error('series_end_date')<p class="tw-form-error text-red-500">{{ $message }}</p>@enderror
                </div>

            </div>

            {{-- Day-of-week row (shown only when period = week) --}}
            @php
            $dowNames = [0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'];
            $oldDow = array_map('intval', old('days_of_week', []));
            @endphp
            <div id="days-of-week-row" class="mt-1 pt-4 border-t border-gray-100 {{ old('freq_term') === 'week' ? '' : 'hidden' }}">
                <label class="tw-form-label block mb-2">Repeat On <span class="text-red-500">*</span></label>
                <div class="flex gap-2 flex-wrap" id="dow-pills">
                    @foreach($dowNames as $num => $label)
                    <label class="dow-pill cursor-pointer select-none" for="dow_{{ $num }}">
                        <input type="checkbox" name="days_of_week[]" value="{{ $num }}" id="dow_{{ $num }}"
                            class="sr-only dow-checkbox"
                            {{ in_array($num, $oldDow) ? 'checked' : '' }}>
                        <span class="dow-pill-span block px-4 py-1.5 rounded-full border text-sm font-medium transition
                            {{ in_array($num, $oldDow) ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 text-gray-600 hover:border-blue-400' }}">
                            {{ $label }}
                        </span>
                    </label>
                    @endforeach
                </div>
                @error('days_of_week')<p class="tw-form-error text-red-500 mt-2">{{ $message }}</p>@enderror
            </div>

        </div>

        {{-- ── Cover Image ──────────────────────────────────────────────────── --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-5">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Cover Image <span class="text-red-400 font-normal text-xs ml-1">*</span></h2>
            </div>
            <div class="px-6 py-5">
                <input type="hidden" name="cover_image_id" id="cover_image_id" value="{{ old('cover_image_id') }}">
                <input type="hidden" name="cover_image_path" id="cover_image_path" value="{{ old('cover_image_path') }}">

                <div id="cover-preview" class="{{ old('cover_image_path') ? '' : 'hidden' }} mb-3">
                    <img id="cover-preview-img"
                        src="{{ old('cover_image_path') }}"
                        class="w-full max-w-xs h-32 object-cover rounded-lg border border-gray-200">
                </div>

                <div class="flex gap-3 items-center">
                    <button type="button" id="open-picker-btn"
                        class="text-sm text-indigo-600 border border-indigo-300 rounded px-3 py-1.5 hover:bg-indigo-50 transition">
                        <i class="fas fa-images mr-1"></i>
                        <span id="picker-btn-label">{{ old('cover_image_path') ? 'Change Image' : 'Pick from Media Library' }}</span>
                    </button>
                    <button type="button" id="clear-image-btn"
                        class="{{ old('cover_image_path') ? '' : 'hidden' }} text-sm text-red-400 hover:text-red-600">
                        <i class="fas fa-times mr-1"></i>Remove
                    </button>
                </div>
                <p id="cover-image-error" class="hidden text-red-500 text-xs mt-2">Please select a cover image.</p>
            </div>
        </div>

        {{-- Image Picker Modal — starts hidden; JS adds 'flex' when opening --}}
        <div id="image-picker-modal"
            data-images-url="{{ url('/admin/mediafile/images') }}"
            class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl flex flex-col" style="max-height:80vh">
                <div class="flex justify-between items-center px-6 py-4 border-b flex-shrink-0">
                    <h2 class="text-base font-semibold">Pick a Cover Image</h2>
                    <div class="flex items-center gap-3">
                        <button type="button" id="add-media-btn"
                            data-upload-url="{{ url('/admin/mediafile/image/create') }}"
                            class="text-xs text-green-700 border border-green-400 rounded px-3 py-1.5 hover:bg-green-50 transition flex items-center gap-1">
                            <i class="fas fa-plus text-xs"></i> Add Media image
                        </button>
                        <button type="button" id="close-picker-btn" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                    </div>
                </div>
                <div class="px-6 py-4 flex-1 overflow-y-auto">
                    <div id="folder-tabs" class="hidden flex flex-wrap gap-1 mb-3 pb-3 border-b border-gray-100"></div>
                    <p id="picker-loading" class="text-sm text-gray-400 py-4 text-center">Loading images…</p>
                    <p id="picker-empty" class="hidden text-sm text-gray-500 py-4">
                        No images in the media library. Click <strong>Add Media image</strong> above to upload.
                    </p>
                    <div id="picker-grid" class="hidden gap-3" style="grid-template-columns: repeat(3, minmax(0, 1fr))"></div>
                </div>
                <div class="flex justify-end px-6 py-3 border-t flex-shrink-0">
                    <button type="button" id="picker-done-btn"
                        class="blue-bg text-white text-sm px-4 py-1.5 rounded">Done</button>
                </div>
            </div>
        </div>

        {{-- Upload modal (nested, z-60) --}}
        <div id="upload-media-modal"
            data-store-url="{{ url('/admin/mediafile/image/create') }}"
            class="hidden fixed inset-0 bg-black bg-opacity-60 z-60 items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                <div class="flex justify-between items-center px-6 py-3 border-b">
                    <h2 class="text-sm font-semibold">Upload New Image</h2>
                    <button type="button" id="close-upload-modal-btn" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <input type="hidden" id="upload-csrf" value="{{ csrf_token() }}">
                    <div id="upload-result" class="hidden text-sm rounded px-3 py-2"></div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Image Name <span class="text-red-500">*</span></label>
                        <input type="text" id="upload-name" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400" placeholder="e.g. Sunday Worship">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Image File <span class="text-red-500">*</span></label>
                        <input type="file" id="upload-file" accept=".jpg,.jpeg,.png,.wmp" class="w-full text-sm border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Description</label>
                        <input type="text" id="upload-description" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400" placeholder="Optional">
                    </div>
                </div>
                <div class="flex justify-end gap-2 px-6 py-3 border-t">
                    <button type="button" id="close-upload-modal-btn2" class="text-sm text-gray-500 border border-gray-300 rounded px-4 py-1.5 hover:bg-gray-50">Cancel</button>
                    <button type="button" id="upload-submit-btn" class="blue-bg text-white text-sm px-4 py-1.5 rounded">Upload</button>
                </div>
            </div>
        </div>

        {{-- ── Row 6: Event Options (Toggles) ──────────────────────────────── --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Event Options</h2>
            </div>
            <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-5">

                @php
                $toggles = [
                ['name' => 'enable_attendance', 'id' => 'toggle_attendance', 'label' => 'Attendance Tracking', 'desc' => 'Enable QR check-in for this event', 'default' => true],
                ['name' => 'publish_to_web', 'id' => 'toggle_web', 'label' => 'Publish to Website', 'desc' => 'Show on the public website', 'default' => true],
                ['name' => 'enable_gallery', 'id' => 'toggle_gallery', 'label' => 'Enable Gallery', 'desc' => 'Allow photo uploads for this event', 'default' => true],
                ];
                @endphp

                @foreach($toggles as $t)
                @php $checked = old($t['name'], $t['default']); @endphp
                <label class="flex items-center gap-3 cursor-pointer select-none"
                    for="{{ $t['id'] }}">
                    {{-- Toggle track + thumb, styled with inline CSS so no compiled Tailwind needed --}}
                    <div class="ev-toggle-track flex-shrink-0"
                        style="
                            position:relative; width:44px; height:24px;
                            border-radius:12px; cursor:pointer;
                            background:{{ $checked ? '#2563EB' : '#D1D5DB' }};
                            transition:background .2s;
                         ">
                        <input type="checkbox" name="{{ $t['name'] }}" value="1"
                            id="{{ $t['id'] }}"
                            {{ $checked ? 'checked' : '' }}
                            class="ev-toggle-input"
                            style="position:absolute;opacity:0;width:100%;height:100%;margin:0;cursor:pointer;z-index:1;">
                        <span class="ev-toggle-thumb"
                            style="
                                 position:absolute; top:3px;
                                 left:{{ $checked ? '23px' : '3px' }};
                                 width:18px; height:18px;
                                 background:#fff; border-radius:50%;
                                 box-shadow:0 1px 3px rgba(0,0,0,.25);
                                 transition:left .2s, background .2s;
                                 pointer-events:none;
                              "></span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $t['label'] }}</p>
                        <p class="text-xs text-gray-400">{{ $t['desc'] }}</p>
                    </div>
                </label>
                @endforeach

            </div>

            {{-- Attendance scope (shown when Attendance Tracking is on) --}}
            @php
            $oldScope = old('attendance_scope', 'all');
            $oldGroup = old('attendance_group_id', '');
            $attEnabled = old('enable_attendance', false);
            @endphp
            <div id="attendance-scope-panel"
                class="{{ $attEnabled ? '' : 'hidden' }} px-6 pb-5 pt-1 border-t border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Who can be checked in?</p>
                <div class="flex flex-wrap gap-3 mb-3" id="att-scope-group">
                    @foreach(['all' => ['label' => 'All Members', 'icon' => 'fa-users'], 'group' => ['label' => 'A Group', 'icon' => 'fa-layer-group']] as $val => $opt)
                    <label class="att-scope-pill cursor-pointer border-2 rounded-lg px-4 py-2.5 flex items-center gap-2.5 transition select-none
                        {{ $oldScope === $val ? 'border-blue-600 bg-blue-50' : 'border-gray-200 bg-white hover:border-blue-300 hover:bg-blue-50' }}">
                        <input type="radio" name="attendance_scope" value="{{ $val }}" class="sr-only"
                            {{ $oldScope === $val ? 'checked' : '' }}>
                        <i class="fas {{ $opt['icon'] }} w-4 {{ $oldScope === $val ? 'text-blue-500' : 'text-gray-400' }}"></i>
                        <span class="text-sm font-medium {{ $oldScope === $val ? 'text-blue-700' : 'text-gray-700' }}">{{ $opt['label'] }}</span>
                    </label>
                    @endforeach
                </div>
                <div id="att-group-select" class="{{ $oldScope === 'group' ? '' : 'hidden' }} max-w-xs">
                    <select name="attendance_group_id" class="tw-form-control w-full text-sm">
                        <option value="">Select a group…</option>
                        @foreach($groups as $g)
                        <option value="{{ $g->id }}" {{ (string)$oldGroup === (string)$g->id ? 'selected' : '' }}>
                            {{ $g->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- ── Submit ───────────────────────────────────────────────────────── --}}
        <div class="flex items-center gap-3 pb-10">
            <button type="submit"
                class="px-6 py-2 blue-bg text-white text-sm font-medium rounded-lg hover:opacity-90 transition">
                Create Event
            </button>
            <a href="{{ url('/admin/events') }}"
                class="px-6 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                Cancel
            </a>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        // ── Pill selection highlight ─────────────────────────────────────────
        function setupPillGroup(groupId) {
            var group = document.getElementById(groupId);
            if (!group) return;

            function activatePill(activeLbl) {
                group.querySelectorAll('label.ev-pill').forEach(function(lbl) {
                    var on = lbl === activeLbl;
                    // Border + background
                    lbl.classList.toggle('border-blue-600', on);
                    lbl.classList.toggle('bg-blue-50', on);
                    lbl.classList.toggle('border-gray-200', !on);
                    lbl.classList.toggle('bg-white', !on);
                    // Suppress hover classes on selected pill so blue stays solid
                    lbl.classList.toggle('hover:border-blue-300', !on);
                    lbl.classList.toggle('hover:bg-blue-50', !on);
                    // Icon colour
                    var icon = lbl.querySelector('i.fas');
                    if (icon) {
                        icon.classList.toggle('text-blue-500', on);
                        icon.classList.toggle('text-gray-400', !on);
                    }
                    // Title colour
                    var title = lbl.querySelector('p:first-of-type');
                    if (title) {
                        title.classList.toggle('text-blue-700', on);
                        title.classList.toggle('text-gray-800', !on);
                    }
                });
            }

            group.addEventListener('change', function(e) {
                if (e.target.type === 'radio' && e.target.checked) {
                    activatePill(e.target.closest('label'));
                }
            });

            // Sync initial state from checked radio
            var checked = group.querySelector('input[type=radio]:checked');
            if (checked) activatePill(checked.closest('label'));
        }
        setupPillGroup('event-type-group');
        setupPillGroup('schedule-type-group');

        // Declare shared DOM refs before any function that references them
        var dateInput = document.getElementById('event_date');
        var timeInput = document.getElementById('start_time');
        var durationInput = document.getElementById('duration');
        var preview = document.getElementById('end-time-preview');

        // ── Show/hide recurring section ──────────────────────────────────────
        const scheduleGroup = document.getElementById('schedule-type-group');
        const recurringBlock = document.getElementById('recurring-section');
        const freqInput = document.getElementById('freq');
        const freqTermInput = document.getElementById('freq_term');
        const seriesEnd = document.getElementById('series_end_date');
        const monthTypeRow = document.getElementById('month-type-row');
        const monthTypeSelect = document.getElementById('month_type');

        function toggleRecurring() {
            const selected = scheduleGroup.querySelector('input[type=radio]:checked');
            const isRecurring = selected && selected.value === '1';
            recurringBlock.classList.toggle('hidden', !isRecurring);
            // Toggle required so validation fires correctly
            if (freqInput) freqInput.required = isRecurring;
            if (freqTermInput) freqTermInput.required = isRecurring;
            if (seriesEnd) seriesEnd.required = isRecurring;
            toggleMonthRow();
        }
        scheduleGroup.addEventListener('change', toggleRecurring);
        toggleRecurring();

        // ── Day-of-week row (show/hide + pill toggle) ────────────────────────
        const dowRow = document.getElementById('days-of-week-row');
        const dowCheckboxes = document.querySelectorAll('.dow-checkbox');

        function syncDowRequired() {
            dowCheckboxes.forEach(function(cb) {
                cb.required = false;
            });
            if (dowRow && !dowRow.classList.contains('hidden')) {
                var anyChecked = Array.from(dowCheckboxes).some(function(cb) {
                    return cb.checked;
                });
                if (!anyChecked && dowCheckboxes.length) dowCheckboxes[0].required = true;
            }
        }

        function autoSelectDayFromDate() {
            if (!dateInput || !dateInput.value) return;
            var d = new Date(dateInput.value + 'T00:00:00');
            var dayNum = d.getDay(); // 0=Sun … 6=Sat
            dowCheckboxes.forEach(function(cb) {
                var isMatch = parseInt(cb.value) === dayNum;
                cb.checked = isMatch;
                var span = cb.closest('label').querySelector('.dow-pill-span');
                if (span) {
                    span.classList.toggle('bg-blue-600', isMatch);
                    span.classList.toggle('border-blue-600', isMatch);
                    span.classList.toggle('text-white', isMatch);
                    span.classList.toggle('text-gray-600', !isMatch);
                    span.classList.toggle('border-gray-300', !isMatch);
                }
            });
            syncDowRequired();
        }

        function toggleDowRow() {
            var isWeekly = freqTermInput && freqTermInput.value === 'week';
            var isRecurring = scheduleGroup.querySelector('input[type=radio]:checked');
            var show = isWeekly && isRecurring && isRecurring.value === '1';
            if (dowRow) dowRow.classList.toggle('hidden', !show);
            if (show) autoSelectDayFromDate();
            syncDowRequired();
        }

        // ── Month repeat options (shown only when period = month) ───────────
        const _DAY_NAMES = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const _ORDINALS = ['', '1st', '2nd', '3rd', '4th', '5th'];

        function getWeekOfMonth(d) {
            var firstDay = new Date(d.getFullYear(), d.getMonth(), 1);
            var firstOccurrence = (d.getDay() - firstDay.getDay() + 7) % 7 + 1;
            return Math.floor((d.getDate() - firstOccurrence) / 7) + 1;
        }

        function updateMonthOptions() {
            if (!monthTypeSelect || !dateInput || !dateInput.value) return;
            var d = new Date(dateInput.value + 'T00:00:00');
            var dayOfMonth = d.getDate();
            var weekNum = getWeekOfMonth(d);
            var dayName = _DAY_NAMES[d.getDay()];
            var ordinal = _ORDINALS[weekNum] || (weekNum + 'th');
            var val1 = 'day_' + dayOfMonth;
            var val2 = 'week_' + weekNum + '_' + d.getDay();
            var cur = monthTypeSelect.value;
            monthTypeSelect.innerHTML =
                '<option value="">Select option…</option>' +
                '<option value="' + val1 + '"' + (cur === val1 ? ' selected' : '') +
                '>Monthly on day ' + dayOfMonth + '</option>' +
                '<option value="' + val2 + '"' + (cur === val2 ? ' selected' : '') +
                '>Monthly on ' + ordinal + ' ' + dayName + '</option>';
        }

        function toggleMonthRow() {
            var isMonthly = freqTermInput && freqTermInput.value === 'month';
            var activeSched = scheduleGroup.querySelector('input[type=radio]:checked');
            var show = isMonthly && activeSched && activeSched.value === '1';
            if (monthTypeRow) monthTypeRow.classList.toggle('hidden', !show);
            if (monthTypeSelect) monthTypeSelect.required = !!show;
            if (show) updateMonthOptions();
        }

        if (freqTermInput) freqTermInput.addEventListener('change', function() {
            toggleDowRow();
            toggleMonthRow();
        });
        scheduleGroup.addEventListener('change', toggleDowRow);
        if (dateInput) dateInput.addEventListener('change', function() {
            if (dowRow && !dowRow.classList.contains('hidden')) autoSelectDayFromDate();
            if (monthTypeRow && !monthTypeRow.classList.contains('hidden')) updateMonthOptions();
        });
        toggleDowRow();
        toggleMonthRow();

        // ── Toggle switches (visual + attendance panel) ──────────────────────
        document.querySelectorAll('.ev-toggle-input').forEach(function(input) {
            input.addEventListener('change', function() {
                var track = input.closest('.ev-toggle-track');
                var thumb = track ? track.querySelector('.ev-toggle-thumb') : null;
                if (track) track.style.background = input.checked ? '#2563EB' : '#D1D5DB';
                if (thumb) thumb.style.left = input.checked ? '23px' : '3px';
                if (input.name === 'enable_attendance') {
                    var panel = document.getElementById('attendance-scope-panel');
                    if (panel) panel.classList.toggle('hidden', !input.checked);
                }
            });
        });

        // ── Attendance scope pills ───────────────────────────────────────────
        var attScopeGroup = document.getElementById('att-scope-group');
        var attGroupSelect = document.getElementById('att-group-select');

        function activateAttScope(activeLbl) {
            if (!attScopeGroup) return;
            attScopeGroup.querySelectorAll('label.att-scope-pill').forEach(function(lbl) {
                var on = lbl === activeLbl;
                lbl.classList.toggle('border-blue-600', on);
                lbl.classList.toggle('bg-blue-50', on);
                lbl.classList.toggle('border-gray-200', !on);
                lbl.classList.toggle('bg-white', !on);
                lbl.classList.toggle('hover:border-blue-300', !on);
                lbl.classList.toggle('hover:bg-blue-50', !on);
                var icon = lbl.querySelector('i.fas');
                if (icon) {
                    icon.classList.toggle('text-blue-500', on);
                    icon.classList.toggle('text-gray-400', !on);
                }
                var span = lbl.querySelector('span');
                if (span) {
                    span.classList.toggle('text-blue-700', on);
                    span.classList.toggle('text-gray-700', !on);
                }
            });
        }

        if (attScopeGroup) {
            attScopeGroup.addEventListener('change', function(e) {
                if (e.target.type === 'radio' && e.target.checked) {
                    activateAttScope(e.target.closest('label'));
                    if (attGroupSelect) attGroupSelect.classList.toggle('hidden', e.target.value !== 'group');
                }
            });
            var attChecked = attScopeGroup.querySelector('input[type=radio]:checked');
            if (attChecked) activateAttScope(attChecked.closest('label'));
        }

        // Pill toggle on click
        dowCheckboxes.forEach(function(cb) {
            cb.addEventListener('change', function() {
                var span = cb.closest('label').querySelector('.dow-pill-span');
                if (!span) return;
                span.classList.toggle('bg-blue-600', cb.checked);
                span.classList.toggle('border-blue-600', cb.checked);
                span.classList.toggle('text-white', cb.checked);
                span.classList.toggle('text-gray-600', !cb.checked);
                span.classList.toggle('border-gray-300', !cb.checked);
                syncDowRequired();
            });
        });

        // ── End-time preview ─────────────────────────────────────────────────
        function updateEndPreview() {
            const date = dateInput ? dateInput.value : '';
            const time = timeInput ? timeInput.value : '';
            const duration = durationInput ? parseInt(durationInput.value, 10) : 0;
            if (!date || !time || !duration) {
                if (preview) preview.textContent = '';
                return;
            }
            const start = new Date(date + 'T' + time);
            const end = new Date(start.getTime() + duration * 60000);
            const fmt = (d) => d.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
            if (preview) preview.textContent = 'Ends at ' + fmt(end);
        }
        if (dateInput) dateInput.addEventListener('change', updateEndPreview);
        if (timeInput) timeInput.addEventListener('change', updateEndPreview);
        if (durationInput) durationInput.addEventListener('change', updateEndPreview);
        updateEndPreview();

        (function() {
            const modal = document.getElementById('image-picker-modal');
            const openBtn = document.getElementById('open-picker-btn');
            const closeBtn = document.getElementById('close-picker-btn');
            const doneBtn = document.getElementById('picker-done-btn');
            const grid = document.getElementById('picker-grid');
            const loadingMsg = document.getElementById('picker-loading');
            const emptyMsg = document.getElementById('picker-empty');
            const folderTabs = document.getElementById('folder-tabs');
            const previewWrap = document.getElementById('cover-preview');
            const previewImg = document.getElementById('cover-preview-img');
            const clearBtn = document.getElementById('clear-image-btn');
            const btnLabel = document.getElementById('picker-btn-label');
            const inputId = document.getElementById('cover_image_id');
            const inputPath = document.getElementById('cover_image_path');
            const coverError = document.getElementById('cover-image-error');

            var selectedId = inputId ? inputId.value : '';
            var selectedPath = inputPath ? inputPath.value : '';
            var imagesLoaded = false;
            var allImages = [];
            var currentFolder = 'all';

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                if (!imagesLoaded) loadImages();
            }

            function closeModal() {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }

            function renderFolderTabs(folders) {
                if (!folderTabs) return;
                folderTabs.innerHTML = '';
                var allFolders = ['all'].concat(folders);
                allFolders.forEach(function(f) {
                    var btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = f === 'all' ? 'All' : f;
                    btn.dataset.folder = f;
                    var isActive = currentFolder === f;
                    btn.className = 'text-xs px-3 py-1 rounded-full border transition ' +
                        (isActive ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-300 hover:border-indigo-400');
                    btn.addEventListener('click', function() {
                        currentFolder = f;
                        renderFolderTabs(folders);
                        renderGrid();
                    });
                    folderTabs.appendChild(btn);
                });
                folderTabs.classList.remove('hidden');
            }

            function renderGrid() {
                var images = currentFolder === 'all' ?
                    allImages :
                    allImages.filter(function(img) {
                        return img.folder === currentFolder;
                    });
                grid.innerHTML = '';
                if (images.length === 0) {
                    emptyMsg.classList.remove('hidden');
                    grid.classList.add('hidden');
                    grid.style.display = '';
                    return;
                }
                emptyMsg.classList.add('hidden');
                images.forEach(function(img) {
                    var div = document.createElement('div');
                    div.className = 'cursor-pointer border-2 rounded overflow-hidden transition';
                    div.dataset.id = img.id;
                    div.dataset.url = img.url;
                    div.classList.add(selectedId == img.id ? 'border-indigo-500' : 'border-transparent');
                    div.innerHTML = '<img src="' + img.url + '" class="w-full h-24 object-cover">' +
                        '<p class="text-xs text-gray-600 px-1 py-1 truncate">' + (img.name || '') + '</p>';
                    div.addEventListener('click', function() {
                        grid.querySelectorAll('[data-id]').forEach(function(el) {
                            el.classList.remove('border-indigo-500');
                            el.classList.add('border-transparent');
                        });
                        div.classList.add('border-indigo-500');
                        div.classList.remove('border-transparent');
                        selectedId = img.id;
                        selectedPath = img.url;
                    });
                    grid.appendChild(div);
                });
                grid.classList.remove('hidden');
                grid.style.display = 'grid';
            }

            function loadImages() {
                loadingMsg.classList.remove('hidden');
                emptyMsg.classList.add('hidden');
                grid.classList.add('hidden');
                grid.style.display = '';
                if (folderTabs) folderTabs.classList.add('hidden');

                fetch(modal.dataset.imagesUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(function(r) {
                        return r.json();
                    })
                    .then(function(res) {
                        loadingMsg.classList.add('hidden');
                        allImages = res.data || [];
                        var folders = res.folders || [];
                        if (allImages.length === 0) {
                            emptyMsg.classList.remove('hidden');
                            return;
                        }
                        renderFolderTabs(folders);
                        renderGrid();
                        imagesLoaded = true;
                    })
                    .catch(function() {
                        loadingMsg.textContent = 'Failed to load images.';
                    });
            }

            function applySelection() {
                if (!selectedId) {
                    closeModal();
                    return;
                }
                inputId.value = selectedId;
                inputPath.value = selectedPath;
                previewImg.src = selectedPath;
                previewWrap.classList.remove('hidden');
                clearBtn.classList.remove('hidden');
                btnLabel.textContent = 'Change Image';
                if (coverError) coverError.classList.add('hidden');
                closeModal();
            }

            function clearImage() {
                selectedId = selectedPath = '';
                inputId.value = inputPath.value = '';
                previewWrap.classList.add('hidden');
                clearBtn.classList.add('hidden');
                btnLabel.textContent = 'Pick from Media Library';
                renderGrid();
            }

            if (openBtn) openBtn.addEventListener('click', openModal);
            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (doneBtn) doneBtn.addEventListener('click', applySelection);
            if (clearBtn) clearBtn.addEventListener('click', clearImage);
            if (modal) modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            // ── Upload media modal ───────────────────────────────────────────────
            var uploadModal = document.getElementById('upload-media-modal');
            var addMediaBtn = document.getElementById('add-media-btn');
            var closeUploadBtn = document.getElementById('close-upload-modal-btn');
            var closeUploadBtn2 = document.getElementById('close-upload-modal-btn2');
            var uploadSubmitBtn = document.getElementById('upload-submit-btn');
            var uploadResult = document.getElementById('upload-result');

            function openUploadModal() {
                closeModal();
                document.getElementById('upload-name').value = '';
                document.getElementById('upload-file').value = '';
                document.getElementById('upload-description').value = '';
                uploadResult.className = 'hidden text-sm rounded px-3 py-2';
                uploadResult.textContent = '';
                uploadModal.classList.remove('hidden');
                uploadModal.classList.add('flex');
            }

            function closeUploadModal(refresh) {
                uploadModal.classList.remove('flex');
                uploadModal.classList.add('hidden');
                if (refresh) {
                    imagesLoaded = false;
                }
                openModal();
            }

            if (addMediaBtn) addMediaBtn.addEventListener('click', openUploadModal);
            if (closeUploadBtn) closeUploadBtn.addEventListener('click', function() {
                closeUploadModal(false);
            });
            if (closeUploadBtn2) closeUploadBtn2.addEventListener('click', function() {
                closeUploadModal(false);
            });
            if (uploadModal) uploadModal.addEventListener('click', function(e) {
                if (e.target === uploadModal) closeUploadModal(false);
            });

            if (uploadSubmitBtn) {
                uploadSubmitBtn.addEventListener('click', function() {
                    var name = document.getElementById('upload-name').value.trim();
                    var file = document.getElementById('upload-file').files[0];
                    if (!name || !file) {
                        uploadResult.className = 'text-sm rounded px-3 py-2 bg-red-50 text-red-600 border border-red-200';
                        uploadResult.textContent = 'Name and image file are required.';
                        return;
                    }
                    var formData = new FormData();
                    formData.append('name', name);
                    formData.append('image', file);
                    formData.append('description', document.getElementById('upload-description').value);
                    formData.append('_token', document.getElementById('upload-csrf').value);

                    uploadSubmitBtn.disabled = true;
                    uploadSubmitBtn.textContent = 'Uploading…';

                    fetch(uploadModal.dataset.storeUrl, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(function(r) {
                            return r.json().then(function(data) {
                                return {
                                    status: r.status,
                                    data: data
                                };
                            });
                        })
                        .then(function(res) {
                            uploadSubmitBtn.disabled = false;
                            uploadSubmitBtn.textContent = 'Upload';
                            if (res.status === 422 && res.data.errors) {
                                var msgs = Object.values(res.data.errors).flat();
                                uploadResult.className = 'text-sm rounded px-3 py-2 bg-red-50 text-red-600 border border-red-200';
                                uploadResult.textContent = msgs[0] || 'Validation failed.';
                            } else if (res.data.success) {
                                uploadResult.className = 'text-sm rounded px-3 py-2 bg-green-50 text-green-700 border border-green-200';
                                uploadResult.textContent = res.data.success;
                                setTimeout(function() {
                                    closeUploadModal(true);
                                }, 800);
                            } else {
                                uploadResult.className = 'text-sm rounded px-3 py-2 bg-red-50 text-red-600 border border-red-200';
                                uploadResult.textContent = res.data.error || 'Upload failed.';
                            }
                        })
                        .catch(function() {
                            uploadSubmitBtn.disabled = false;
                            uploadSubmitBtn.textContent = 'Upload';
                            uploadResult.className = 'text-sm rounded px-3 py-2 bg-red-50 text-red-600 border border-red-200';
                            uploadResult.textContent = 'Upload failed. Please try again.';
                        });
                });
            }

            var form = document.getElementById('event-create-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!inputPath || !inputPath.value) {
                        e.preventDefault();
                        if (coverError) coverError.classList.remove('hidden');
                        openBtn.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                });
            }
        })();

    })();
</script>

@endpush