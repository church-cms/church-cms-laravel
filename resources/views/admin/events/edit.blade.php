@extends('layouts.admin.layout')

@section('content')
@php
//$schedule = old('schedule', $event->repeats == 1 ? '1' : '0');
$schedule = (string) old('schedule', $event->repeats == 1 ? '1' : '0');
$selectType = old('select_type', $event->select_type ?? 'public');
$oldDow = array_map('intval', old('days_of_week', $event->days_of_week ?? []));
$dowNames = [0=>'Sun',1=>'Mon',2=>'Tue',3=>'Wed',4=>'Thu',5=>'Fri',6=>'Sat'];
$coverPath = old('cover_image_path', $event->image ?? '');
$coverPreviewUrl = '';
if ($coverPath) {
$coverPreviewUrl = str_starts_with($coverPath, 'http')
? $coverPath
: \Storage::disk('public')->url($coverPath);
}
@endphp

<div class="w-full max-w-3xl">

    <h1 class="admin-h1 mb-6 flex items-center gap-3">
        <a href="{{ route('admin.events.show', $event->id) }}"
            class="rounded-full bg-gray-100 hover:bg-gray-200 p-2 transition">
            <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
        </a>
        Edit Event
    </h1>

    @include('partials.message')

    <form method="POST" action="{{ route('admin.events.storeEdit', $event->id) }}" id="event-edit-form">
        @csrf

        {{-- ── Row 1: Event Type ────────────────────────────────────────────── --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-5">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Event Type <span class="text-red-500">*</span></h2>
            </div>
            <div class="px-6 py-5">
                <div class="flex gap-3 flex-wrap" id="event-type-group">
                    @foreach(['private' => ['label'=>'Private','icon'=>'fa-lock','desc'=>'Members only'],
                    'public' => ['label'=>'Public', 'icon'=>'fa-globe','desc'=>'Open to all'],
                    'online' => ['label'=>'Online', 'icon'=>'fa-video','desc'=>'Virtual event']] as $val => $opt)
                    <label class="event-type-pill flex-1 min-w-[140px] cursor-pointer border-2 rounded-lg px-4 py-3 flex items-center gap-3 transition
                        {{ $selectType === $val ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                        <input type="radio" name="select_type" value="{{ $val }}" class="sr-only"
                            {{ $selectType === $val ? 'checked' : '' }}>
                        <i class="fas {{ $opt['icon'] }} text-gray-400 w-4"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $opt['label'] }}</p>
                            <p class="text-xs text-gray-400">{{ $opt['desc'] }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('select_type')<p class="tw-form-error mt-2">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- ── Row 2: Schedule ──────────────────────────────────────────────── --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-5">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Schedule <span class="text-red-500">*</span></h2>
            </div>


            <div class="px-6 py-5">
                <div class="flex gap-3 flex-wrap" id="schedule-type-group">
                    @foreach(['0'=>['label'=>'One-Time Event','icon'=>'fa-calendar-check','desc'=>'Happens once'],
                    '1'=>['label'=>'Recurring Event','icon'=>'fa-rotate','desc'=>'Repeats on a schedule']] as $val => $opt)

                    <label class="schedule-pill flex-1 min-w-[180px] cursor-pointer border-2 rounded-lg px-4 py-3 flex items-center gap-3 transition
                        {{ $schedule == $val ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                        <input type="radio" name="schedule" value="{{ $val }}" class="sr-only"
                            {{ $schedule == $val ? 'checked' : '' }}>
                        <i class="fas {{ $opt['icon'] }} text-gray-400 w-4"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $opt['label'] }}</p>
                            <p class="text-xs text-gray-400">{{ $opt['desc'] }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('schedule')<p class="tw-form-error mt-2">{{ $message }}</p>@enderror
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
                    <input type="text" name="title" value="{{ old('title', $event->title) }}"
                        class="tw-form-control w-full @error('title') border-red-400 @enderror"
                        placeholder="e.g. Sunday Morning Service">
                    @error('title')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="tw-form-label">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3"
                        class="tw-form-control w-full @error('description') border-red-400 @enderror"
                        placeholder="Brief description of the event">{{ old('description', $event->description) }}</textarea>
                    @error('description')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="tw-form-label">Location <span class="text-red-500">*</span></label>
                    <input type="text" name="location" value="{{ old('location', $event->location) }}"
                        class="tw-form-control w-full @error('location') border-red-400 @enderror"
                        placeholder="Where is this event held?">
                    <p class="text-xs text-gray-400 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Common venues: Sanctuary · Foyer · Community Hall · Study Room
                    </p>
                    @error('location')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Category <span class="text-red-500">*</span></label>
                    <select name="category" class="tw-form-control w-full @error('category') border-red-400 @enderror">
                        <option value="">Select category…</option>
                        @foreach($categories as $val => $label)
                        <option value="{{ $val }}" {{ old('category', $event->category) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Organised By <span class="text-red-500">*</span></label>
                    <input type="text" name="organised_by" value="{{ old('organised_by', $event->organised_by) }}"
                        class="tw-form-control w-full @error('organised_by') border-red-400 @enderror"
                        placeholder="Person or group name">
                    @error('organised_by')<p class="tw-form-error">{{ $message }}</p>@enderror
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
                        value="{{ old('event_date', $eventDate) }}"
                        class="tw-form-control w-full @error('event_date') border-red-400 @enderror">
                    @error('event_date')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Start Time <span class="text-red-500">*</span></label>
                    @php $oldTime = old('start_time', $startTime); @endphp
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
                    @error('start_time')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Duration <span class="text-red-500">*</span></label>
                    <select name="duration" id="duration"
                        class="tw-form-control w-full @error('duration') border-red-400 @enderror">
                        <option value="">Select duration…</option>
                        @foreach([30=>'30 minutes',60=>'1 hour',90=>'1 hour 30 min',
                        120=>'2 hours',180=>'3 hours',240=>'4 hours',480=>'8 hours'] as $mins => $label)
                        <option value="{{ $mins }}"
                            {{ old('duration', $durationMinutes) == $mins ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1" id="end-time-preview"></p>
                    @error('duration')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        {{-- ── Row 5: Recurring Settings ────────────────────────────────────── --}}
        @php
        $freqTerm = old('freq_term', $event->freq_term ?? '');
        @endphp
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-5 {{ $schedule === '1' ? '' : 'hidden' }}" id="recurring-section">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Recurring Settings</h2>
            </div>
            <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-5">

                <div>
                    <label class="tw-form-label">Repeat Every <span class="text-red-500">*</span></label>
                    <input type="number" name="freq" id="freq" min="1"
                        value="{{ old('freq', $event->freq ?? 1) }}"
                        class="tw-form-control w-full @error('freq') border-red-400 @enderror">
                    @error('freq')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Period <span class="text-red-500">*</span></label>
                    <select name="freq_term" id="freq_term"
                        class="tw-form-control w-full @error('freq_term') border-red-400 @enderror">
                        <option value="">Select period…</option>
                        @foreach(['day'=>'Day(s)','week'=>'Week(s)','month'=>'Month(s)','year'=>'Year(s)'] as $val => $lbl)
                        <option value="{{ $val }}" {{ $freqTerm === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                    @error('freq_term')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="tw-form-label">Series Ends On <span class="text-red-500">*</span></label>
                    <input type="date" name="series_end_date" id="series_end_date"
                        value="{{ old('series_end_date', $seriesEndDate) }}"
                        class="tw-form-control w-full @error('series_end_date') border-red-400 @enderror">
                    @error('series_end_date')<p class="tw-form-error">{{ $message }}</p>@enderror
                </div>

            </div>

            {{-- Day-of-week row --}}
            <div id="days-of-week-row" class="mx-6 mt-1 pb-5 pt-4 border-t border-gray-100 {{ $freqTerm === 'week' ? '' : 'hidden' }}">
                <label class="tw-form-label block mb-2">Repeat On <span class="text-red-500">*</span></label>
                <div class="flex gap-2 flex-wrap" id="dow-pills">
                    @foreach($dowNames as $num => $lbl)
                    <label class="dow-pill cursor-pointer select-none" for="dow_{{ $num }}">
                        <input type="checkbox" name="days_of_week[]" value="{{ $num }}" id="dow_{{ $num }}"
                            class="sr-only dow-checkbox"
                            {{ in_array($num, $oldDow) ? 'checked' : '' }}>
                        <span class="dow-pill-span block px-4 py-1.5 rounded-full border text-sm font-medium transition
                            {{ in_array($num, $oldDow) ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 text-gray-600 hover:border-blue-400' }}">
                            {{ $lbl }}
                        </span>
                    </label>
                    @endforeach
                </div>
                @error('days_of_week')<p class="tw-form-error mt-2">{{ $message }}</p>@enderror
            </div>
        </div>
        @php
        $currentImagePath = $coverPath;
        @endphp

        {{-- ── Cover Image ──────────────────────────────────────────────────── --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-5">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Cover Image <span class="text-gray-400 font-normal text-xs ml-1">(optional)</span></h2>
            </div>
            <div class="px-6 py-5">
                <input type="hidden" name="cover_image_id" id="cover_image_id" value="{{ old('cover_image_id') }}">
                <input type="hidden" name="cover_image_path" id="cover_image_path" value="{{ $currentImagePath }}">

                <div id="cover-preview" class="{{ $coverPreviewUrl ? '' : 'hidden' }} mb-3">
                    <img id="cover-preview-img"
                        src="{{ $coverPreviewUrl }}"
                        class="w-full max-w-xs h-32 object-cover rounded-lg border border-gray-200">
                </div>

                <div class="flex gap-3 items-center">
                    <button type="button" id="open-picker-btn"
                        class="text-sm text-indigo-600 border border-indigo-300 rounded px-3 py-1.5 hover:bg-indigo-50 transition">
                        <i class="fas fa-images mr-1"></i>
                        <span id="picker-btn-label">{{ $currentImagePath ? 'Change Image' : 'Pick from Media Library' }}</span>
                    </button>
                    <button type="button" id="clear-image-btn"
                        class="{{ $currentImagePath ? '' : 'hidden' }} text-sm text-red-400 hover:text-red-600">
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

        {{-- ── Row 6: Event Options ─────────────────────────────────────────── --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-5">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Event Options</h2>
            </div>
            <div class="px-6 py-5 flex flex-col gap-4">
                @php
                $toggles = [
                ['name'=>'enable_attendance','id'=>'toggle_attendance','label'=>'Attendance Tracking',
                'desc'=>'Enable QR check-in for this event','default'=>(bool)$event->enable_attendance],
                ['name'=>'publish_to_web', 'id'=>'toggle_web', 'label'=>'Publish to Website',
                'desc'=>'Show on the public website', 'default'=>(bool)$event->publish_to_web],
                ['name'=>'enable_gallery', 'id'=>'toggle_gallery', 'label'=>'Enable Gallery',
                'desc'=>'Allow photo uploads for this event','default'=>(bool)$event->enable_gallery],
                ];
                @endphp
                @foreach($toggles as $t)
                @php $checked = old($t['name']) !== null ? (bool)old($t['name']) : $t['default']; @endphp
                <label class="flex items-center gap-3 cursor-pointer select-none" for="{{ $t['id'] }}">
                    <div class="ev-toggle-track flex-shrink-0"
                        style="position:relative;width:44px;height:24px;border-radius:12px;cursor:pointer;
                                background:{{ $checked ? '#2563EB' : '#D1D5DB' }};transition:background .2s;">
                        <input type="checkbox" name="{{ $t['name'] }}" value="1" id="{{ $t['id'] }}"
                            {{ $checked ? 'checked' : '' }} class="ev-toggle-input"
                            style="position:absolute;opacity:0;width:100%;height:100%;margin:0;cursor:pointer;z-index:1;">
                        <span class="ev-toggle-thumb"
                            style="position:absolute;top:3px;left:{{ $checked ? '23px' : '3px' }};
                                     width:18px;height:18px;background:#fff;border-radius:50%;
                                     box-shadow:0 1px 3px rgba(0,0,0,.25);transition:left .2s;pointer-events:none;"></span>
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
            $oldScope = old('attendance_scope', $event->attendance_scope ?? 'all');
            $oldGroup = old('attendance_group_id', $event->attendance_group_id ?? '');
            $attEnabled = old('enable_attendance') !== null
            ? (bool) old('enable_attendance')
            : (bool) $event->enable_attendance;
            @endphp
            <div id="attendance-scope-panel"
                class="{{ $attEnabled ? '' : 'hidden' }} px-6 pb-5 pt-1 border-t border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Who can be checked in?</p>
                <div class="flex flex-wrap gap-3 mb-3" id="att-scope-group">
                    @foreach(['all' => ['label' => 'All Members', 'icon' => 'fa-users']] as $val => $opt)
                    <label class="att-scope-pill cursor-pointer border-2 rounded-lg px-4 py-2.5 flex items-center gap-2.5 transition select-none
                        {{ $oldScope === $val ? 'border-blue-600 bg-blue-50' : 'border-gray-200 bg-white hover:border-blue-300 hover:bg-blue-50' }}">
                        <input type="radio" name="attendance_scope" value="{{ $val }}" class="sr-only"
                            {{ $oldScope === $val ? 'checked' : '' }}>
                        <i class="fas {{ $opt['icon'] }} w-4 {{ $oldScope === $val ? 'text-blue-500' : 'text-gray-400' }}"></i>
                        <span class="text-sm font-medium {{ $oldScope === $val ? 'text-blue-700' : 'text-gray-700' }}">{{ $opt['label'] }}</span>
                    </label>
                    @endforeach
                </div>
                <!-- <div id="att-group-select" class="{{ $oldScope === 'group' ? '' : 'hidden' }} max-w-xs">
                    <select name="attendance_group_id" class="tw-form-control w-full text-sm">
                        <option value="">Select a group…</option>
                        @foreach($groups as $g)
                        <option value="{{ $g->id }}" {{ (string)$oldGroup === (string)$g->id ? 'selected' : '' }}>
                            {{ $g->name }}
                        </option>
                        @endforeach
                    </select>
                </div> -->
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-3 mb-8">
            <button type="submit"
                class="text-sm rounded px-4 py-2 flex items-center gap-2 btn btn-primary submit-btn">
                <i class="fas fa-save text-xs"></i> Save Changes
            </button>
            <a href="{{ route('admin.events.show', $event->id) }}"
                class="text-sm text-gray-500 hover:text-gray-700 transition">Cancel</a>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        // ── Pill highlight ───────────────────────────────────────────────
        function setupPillGroup(groupId) {
            var group = document.getElementById(groupId);
            if (!group) return;
            group.addEventListener('change', function(e) {
                group.querySelectorAll('label').forEach(function(lbl) {
                    lbl.classList.remove('border-blue-600', 'bg-blue-50');
                    lbl.classList.add('border-gray-200');
                });
                if (e.target.checked) {
                    e.target.closest('label').classList.add('border-blue-600', 'bg-blue-50');
                    e.target.closest('label').classList.remove('border-gray-200');
                }
            });
            group.querySelectorAll('input[type=radio]').forEach(function(r) {
                if (r.checked) {
                    r.closest('label').classList.add('border-blue-600', 'bg-blue-50');
                    r.closest('label').classList.remove('border-gray-200');
                }
            });
        }
        setupPillGroup('event-type-group');
        setupPillGroup('schedule-type-group');

        // ── Show/hide recurring section ──────────────────────────────────
        var scheduleGroup = document.getElementById('schedule-type-group');
        var recurringBlock = document.getElementById('recurring-section');
        var freqInput = document.getElementById('freq');
        var freqTermInput = document.getElementById('freq_term');
        var seriesEnd = document.getElementById('series_end_date');

        function toggleRecurring() {
            var sel = scheduleGroup.querySelector('input[type=radio]:checked');
            var isRec = sel && sel.value === '1';
            recurringBlock.classList.toggle('hidden', !isRec);
            if (freqInput) freqInput.required = isRec;
            if (freqTermInput) freqTermInput.required = isRec;
            if (seriesEnd) seriesEnd.required = isRec;
        }
        scheduleGroup.addEventListener('change', toggleRecurring);
        toggleRecurring();

        // ── Day-of-week row ──────────────────────────────────────────────
        var dowRow = document.getElementById('days-of-week-row');
        var dowCheckboxes = document.querySelectorAll('.dow-checkbox');
        var dateInput = document.getElementById('event_date');

        function syncDowRequired() {
            dowCheckboxes.forEach(function(cb) {
                cb.required = false;
            });
            if (dowRow && !dowRow.classList.contains('hidden')) {
                var any = Array.from(dowCheckboxes).some(function(cb) {
                    return cb.checked;
                });
                if (!any && dowCheckboxes.length) dowCheckboxes[0].required = true;
            }
        }

        function autoSelectDayFromDate() {
            if (!dateInput || !dateInput.value) return;
            var dayNum = new Date(dateInput.value + 'T00:00:00').getDay();
            dowCheckboxes.forEach(function(cb) {
                var match = parseInt(cb.value) === dayNum;
                cb.checked = match;
                var span = cb.closest('label').querySelector('.dow-pill-span');
                if (span) {
                    span.classList.toggle('bg-blue-600', match);
                    span.classList.toggle('border-blue-600', match);
                    span.classList.toggle('text-white', match);
                    span.classList.toggle('text-gray-600', !match);
                    span.classList.toggle('border-gray-300', !match);
                }
            });
            syncDowRequired();
        }

        function toggleDowRow() {
            var sel = scheduleGroup.querySelector('input[type=radio]:checked');
            var weekly = freqTermInput && freqTermInput.value === 'week';
            var show = weekly && sel && sel.value === '1';
            if (dowRow) dowRow.classList.toggle('hidden', !show);
            // only auto-select when no days are already checked (i.e. new selection)
            if (show) {
                var hasChecked = Array.from(dowCheckboxes).some(function(cb) {
                    return cb.checked;
                });
                if (!hasChecked) autoSelectDayFromDate();
            }
            syncDowRequired();
        }

        if (freqTermInput) freqTermInput.addEventListener('change', toggleDowRow);
        scheduleGroup.addEventListener('change', toggleDowRow);
        if (dateInput) dateInput.addEventListener('change', function() {
            if (dowRow && !dowRow.classList.contains('hidden')) autoSelectDayFromDate();
        });
        toggleDowRow();

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

        // ── End-time preview ─────────────────────────────────────────────
        var timeInput = document.getElementById('start_time');
        var durationInput = document.getElementById('duration');
        var preview = document.getElementById('end-time-preview');

        function updateEndPreview() {
            var date = dateInput ? dateInput.value : '';
            var time = timeInput ? timeInput.value : '';
            var duration = durationInput ? parseInt(durationInput.value, 10) : 0;
            if (!date || !time || !duration) {
                if (preview) preview.textContent = '';
                return;
            }
            var start = new Date(date + 'T' + time);
            var end = new Date(start.getTime() + duration * 60000);
            if (preview) preview.textContent = 'Ends at ' + end.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        if (dateInput) dateInput.addEventListener('change', updateEndPreview);
        if (timeInput) timeInput.addEventListener('change', updateEndPreview);
        if (durationInput) durationInput.addEventListener('change', updateEndPreview);
        updateEndPreview();

        // ── Toggle switches ──────────────────────────────────────────────
        var attScopePanel = document.getElementById('attendance-scope-panel');

        document.querySelectorAll('.ev-toggle-input').forEach(function(cb) {
            cb.addEventListener('change', function() {
                var track = cb.closest('.ev-toggle-track');
                var thumb = track ? track.querySelector('.ev-toggle-thumb') : null;
                if (track) track.style.background = cb.checked ? '#2563EB' : '#D1D5DB';
                if (thumb) thumb.style.left = cb.checked ? '23px' : '3px';
                if (cb.id === 'toggle_attendance' && attScopePanel) {
                    attScopePanel.classList.toggle('hidden', !cb.checked);
                }
            });
        });

        // ── Attendance scope pills ────────────────────────────────────────
        var scopeGroup = document.getElementById('att-scope-group');
        var groupSelect = document.getElementById('att-group-select');

        if (scopeGroup) {
            scopeGroup.addEventListener('change', function(e) {
                if (e.target.type !== 'radio') return;
                var on = e.target.value === 'group';
                if (groupSelect) groupSelect.classList.toggle('hidden', !on);
                scopeGroup.querySelectorAll('label.att-scope-pill').forEach(function(lbl) {
                    var sel = lbl.querySelector('input[type=radio]') === e.target;
                    lbl.classList.toggle('border-blue-600', sel);
                    lbl.classList.toggle('bg-blue-50', sel);
                    lbl.classList.toggle('border-gray-200', !sel);
                    lbl.classList.toggle('bg-white', !sel);
                    lbl.classList.toggle('hover:border-blue-300', !sel);
                    lbl.classList.toggle('hover:bg-blue-50', !sel);
                    var icon = lbl.querySelector('i.fas');
                    if (icon) {
                        icon.classList.toggle('text-blue-500', sel);
                        icon.classList.toggle('text-gray-400', !sel);
                    }
                    var span = lbl.querySelector('span');
                    if (span) {
                        span.classList.toggle('text-blue-700', sel);
                        span.classList.toggle('text-gray-700', !sel);
                    }
                });
            });
        }

        // ── Cover image picker ───────────────────────────────────────────────
        const modal = document.getElementById('image-picker-modal');
        const openBtn = document.getElementById('open-picker-btn');
        const closeBtn = document.getElementById('close-picker-btn');
        const doneBtn = document.getElementById('picker-done-btn');
        const grid = document.getElementById('picker-grid');
        const loadingMsg = document.getElementById('picker-loading');
        const emptyMsg = document.getElementById('picker-empty');
        const previewWrap = document.getElementById('cover-preview');
        const previewImg = document.getElementById('cover-preview-img');
        const clearBtn = document.getElementById('clear-image-btn');
        const btnLabel = document.getElementById('picker-btn-label');
        const inputId = document.getElementById('cover_image_id');
        const inputPath = document.getElementById('cover_image_path');

        var selectedId = inputId ? inputId.value : '';
        var selectedPath = inputPath ? inputPath.value : '';
        var imagesLoaded = false;
        var coverError = document.getElementById('cover-image-error');

        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            if (!imagesLoaded) loadImages();
        }

        function closeModal() {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        function loadImages() {
            loadingMsg.classList.remove('hidden');
            emptyMsg.classList.add('hidden');
            grid.classList.add('hidden');
            grid.style.display = '';

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
                    var images = res.data || [];
                    if (images.length === 0) {
                        emptyMsg.classList.remove('hidden');
                        return;
                    }
                    grid.innerHTML = '';
                    images.forEach(function(img) {
                        var div = document.createElement('div');
                        div.className = 'cursor-pointer border-2 rounded overflow-hidden transition';
                        div.dataset.id = img.id;
                        div.dataset.url = img.url;
                        div.dataset.name = img.name || '';
                        div.classList.add(selectedId == img.id ? 'border-indigo-500' : 'border-transparent');
                        div.innerHTML =
                            '<img src="' + img.url + '" class="w-full h-24 object-cover">' +
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
            if (grid) grid.querySelectorAll('[data-id]').forEach(function(el) {
                el.classList.remove('border-indigo-500');
                el.classList.add('border-transparent');
            });
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
                loadImages();
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

        var form = document.querySelector('form[action*="sermon/edit"]');
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
</script>
@endpush