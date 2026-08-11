@extends('layouts.app')

@section('title', $grouplist->group->name ?? 'Group Details')

@section('content')

@php
$group = $grouplist->group;
$totalMembers = App\Models\GroupLink::where('group_id', $grouplist->group_id)->count();
$isAdmin = $grouplist->role === 'group_admin';
$coverImage = $group->cover_image ? url('storage/'.$group->cover_image) : null;

if(request('user_id')){
$sendUrl =='';
}else{
$sendUrl = route('member.group.sendmessage', $grouplist->group_id);
}


$authProfile = auth()->user()->userprofile;
@endphp

{{-- ═══════════════════════════════════════════════════════════════
     COVER PHOTO + GROUP HEADER
════════════════════════════════════════════════════════════════ --}}

<div class="relative -mx-4 sm:-mx-6 lg:-mx-8 mb-0">

    {{-- Cover photo --}}
    <div class="relative h-48 sm:h-64 lg:h-72 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 overflow-hidden">
        @if($coverImage)
        <img src="{{ $coverImage }}" alt="Cover" class="w-full h-full object-cover">
        @else
        <div class="absolute inset-0 flex items-center justify-center opacity-20">
            <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z" />
            </svg>
        </div>
        @endif
        <a href="{{ url('/member/mygrouplist') }}"
            class="absolute top-4 left-4 inline-flex items-center gap-1.5 bg-black bg-opacity-40 hover:bg-opacity-60 text-white text-xs font-medium px-3 py-1.5 rounded-full transition no-underline">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back
        </a>
    </div>

    {{-- Group identity bar --}}
    <div class="bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8">
        <div class="flex items-end gap-4 pb-3">

            {{-- Group avatar — only this overlaps the cover --}}
            <div class="flex-shrink-0 -mt-10 sm:-mt-14 w-20 h-20 sm:w-28 sm:h-28 rounded-xl border-4 border-white shadow-md bg-indigo-100 flex items-center justify-center overflow-hidden">
                @if($coverImage)
                <img src="{{ $coverImage }}" alt="{{ $group->name }}" class="w-full h-full object-cover">
                @else
                <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" />
                </svg>
                @endif
            </div>

            {{-- Name + meta --}}
            <div class="flex-1 min-w-0 pt-3 pb-1">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight truncate">{{ $group->name }}</h1>
                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                    @if($group->groupCategory)
                    <span class="text-xs font-semibold bg-indigo-100 text-indigo-700 px-2.5 py-0.5 rounded-full">
                        {{ $group->groupCategory->name }}
                    </span>
                    @endif
                    @if($group->group_type)
                    <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full">
                        {{ ucfirst($group->group_type) }}
                    </span>
                    @endif
                    <span class="text-xs text-gray-500 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197" />
                        </svg>
                        {{ $totalMembers }} member{{ $totalMembers != 1 ? 's' : '' }}
                    </span>
                    @if( !request('user_id'))
                    <span class="text-xs font-semibold {{ $isAdmin ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }} px-2.5 py-0.5 rounded-full">
                        {{ $isAdmin ? 'Group Admin' : ucfirst($grouplist->role) }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Action buttons --}}
            <div class="flex items-center gap-2 pb-1 flex-shrink-0">
                {{-- Send Message — always visible, scrolls to inline form --}}

                @if($isAdmin=='group_admin' && !request('user_id'))
                <button type="button" onclick="focusMessageForm()"
                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Post Message
                </button>
                @endif


                @if(!request('user_id'))
                {{-- Leave Group --}}
                <button type="button"
                    data-group-id="{{ $grouplist->group_id }}"
                    data-group-name="{{ $group->name }}"
                    onclick="confirmRemoveGroup(this)"
                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 px-4 py-2 rounded-lg transition shadow-sm">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Leave Group
                </button>
                @endif

                {{-- Hidden DELETE form --}}
                @if(!request('user_id'))
                <form id="remove-form-{{ $grouplist->group_id }}"
                    action="{{ route('member.group.remove', $grouplist->group_id) }}"
                    method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
                @endif
            </div>
        </div>

        {{-- Tab nav --}}
        <div class="flex gap-0 -mb-px mt-1">
            <button data-tab="discussion"
                class="tab-btn px-5 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-800 transition">
                <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                Discussion
            </button>
            <button data-tab="members"
                class="tab-btn px-5 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-800 transition">
                <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" />
                </svg>
                Members
                <span class="ml-1 text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded-full">{{ $totalMembers }}</span>
            </button>
            <button data-tab="about"
                class="tab-btn px-5 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-800 transition">
                <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                About
            </button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     TAB PANELS
════════════════════════════════════════════════════════════════ --}}
<div class="mt-4">

    {{-- ── DISCUSSION TAB ─────────────────────────────────────────── --}}
    <div id="tab-discussion" class="tab-panel hidden">

        @if($isAdmin=='group_admin' && !request('user_id'))

        {{-- ══════════════════════════════════════════════════════════
             CREATE POST — Facebook-style composer
        ═══════════════════════════════════════════════════════════ --}}
        <div id="group-message-form-card"
            class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-4">

            {{-- Title bar --}}
            <div class="px-5 py-4 border-b border-gray-100 text-center relative">
                <h3 class="text-base font-bold text-gray-900">Create post</h3>
            </div>

            <form id="group-msg-form" enctype="multipart/form-data">
                @csrf

                {{-- ── Poster identity + mode pill ── --}}
                <div class="flex items-center gap-3 px-5 pt-4 pb-2">
                    {{-- Avatar --}}
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex-shrink-0 overflow-hidden flex items-center justify-center">
                        @if($authProfile && $authProfile->AvatarPath)
                        <img src="{{ $authProfile->AvatarPath }}" alt="" class="w-full h-full object-cover">
                        @else
                        <svg class="w-5 h-5 text-indigo-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z" />
                        </svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 leading-tight">{{ auth()->user()->FullName }}</p>
                        {{-- Mode pill selector --}}
                        <!-- <div class="flex items-center gap-1.5 mt-1">
                            <label class="gf-mode-pill cursor-pointer">
                                <input type="radio" name="mode" value="mail" class="sr-only" checked>
                                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full border transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Email
                                </span>
                            </label>
                            <label class="gf-mode-pill cursor-pointer">
                                <input type="radio" name="mode" value="notification" class="sr-only">
                                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full border transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    Notification
                                </span>
                            </label>
                            <label class="gf-mode-pill cursor-pointer">
                                <input type="radio" name="mode" value="sms" class="sr-only">
                                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full border transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                    SMS
                                </span>
                            </label>
                        </div> -->
                    </div>
                </div>

                <!-- {{-- ── Subject (Email only) ── --}}
                <div id="gf-subject" class="px-5 pt-1 pb-0">
                    <input type="text" name="subject" id="gf-subject-input" maxlength="30"
                        placeholder="Subject…"
                        class="w-full text-sm font-medium text-gray-700 placeholder-gray-400 border-0 border-b border-gray-200 pb-2 focus:outline-none focus:border-indigo-400 transition bg-transparent">
                </div> -->

                {{-- ── Message textarea ── --}}
                <div class="px-5 py-3">
                    <textarea name="message" id="gf-message" rows="4" maxlength="1000"
                        placeholder="Write a message to the group..."
                        class="w-full text-base text-gray-800 placeholder-gray-400 border-0 focus:outline-none resize-none bg-transparent leading-relaxed"></textarea>
                    <p class="text-right text-xs text-gray-300 mt-1">
                        <span id="gf-char-count">0</span> / <span id="gf-char-max">1000</span>
                    </p>
                </div>

                {{-- ── Media / file preview ── --}}
                <div id="gf-preview-area" class="hidden mx-5 mb-3 rounded-xl overflow-hidden border border-gray-200 bg-gray-50 relative">
                    <button type="button" onclick="clearAttachment()"
                        class="absolute top-2 right-2 w-7 h-7 bg-gray-800 bg-opacity-60 hover:bg-opacity-80 text-white rounded-full flex items-center justify-center z-10">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    {{-- Image preview --}}
                    <img id="gf-img-preview" src="" alt="" class="hidden w-full w-10 h-10 object-cover">
                    {{-- Video preview --}}
                    <video id="gf-vid-preview" controls class="hidden w-full max-h-64"></video>
                    {{-- File preview --}}
                    <div id="gf-file-preview" class="hidden flex items-center gap-3 px-4 py-3">
                        <svg class="w-8 h-8 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span id="gf-file-name" class="text-sm font-medium text-gray-700 truncate"></span>
                    </div>
                </div>

                {{-- ── Schedule datetime ── --}}
                <!-- <div id="gf-execute-at" class="hidden mx-5 mb-3">
                    <div class="flex items-center gap-2 bg-indigo-50 border border-indigo-200 rounded-xl px-4 py-2.5">
                        <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <input type="datetime-local" name="executed_at"
                            class="flex-1 text-sm text-indigo-700 bg-transparent border-0 focus:outline-none">
                        <button type="button" onclick="clearSchedule()"
                            class="text-indigo-400 hover:text-indigo-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div> -->

                {{-- Hidden inputs --}}
                <input type="hidden" name="send_later" id="gf-send-later-val" value="false">
                {{-- Single file input (accept changes via JS) --}}
                <input type="file" name="attachments" id="gf-file-input"
                    accept="image/*,video/*,.pdf,.doc,.docx,.csv"
                    class="sr-only" onchange="handleFileSelect(this)">

                {{-- Error box --}}
                <div id="gf-error" class="hidden mx-5 mb-3 text-xs text-red-600 bg-red-50 border border-red-200 rounded-xl px-4 py-2.5"></div>

                {{-- ── "Add to your post" toolbar ── --}}
                <div id="gf-add-toolbar" class="mx-5 mb-4 border border-gray-200 rounded-2xl overflow-hidden">
                    <p class="px-4 py-2 text-xs font-semibold text-gray-500 border-b border-gray-100">Add to your post</p>
                    <div class="flex items-center divide-x divide-gray-100">
                        {{-- Photo --}}
                        <button type="button" onclick="triggerFile('image/*')" title="Photo / Image"
                            class="flex-1 flex flex-col items-center gap-1 py-3 hover:bg-gray-50 transition text-xs text-gray-600 font-medium">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Photo
                        </button>
                        {{-- Video --}}
                        <!-- <button type="button" onclick="triggerFile('video/*')" title="Video"
                            class="flex-1 flex flex-col items-center gap-1 py-3 hover:bg-gray-50 transition text-xs text-gray-600 font-medium">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            Video
                        </button> -->
                        {{-- File --}}
                        <!-- <button type="button" onclick="triggerFile('.pdf,.doc,.docx,.csv')" title="Attachment"
                            class="flex-1 flex flex-col items-center gap-1 py-3 hover:bg-gray-50 transition text-xs text-gray-600 font-medium">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            File
                        </button> -->
                        {{-- Schedule --}}
                        <!-- <button type="button" onclick="toggleSchedule()" title="Schedule"
                            class="flex-1 flex flex-col items-center gap-1 py-3 hover:bg-gray-50 transition text-xs text-gray-600 font-medium" id="gf-schedule-btn">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Schedule
                        </button> -->
                    </div>
                </div>

                {{-- ── Post button ── --}}
                <div class="px-5 pb-5">
                    <button type="button" id="gf-submit-btn" onclick="submitGroupMessage()"
                        class="w-full py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 rounded-xl transition shadow-sm">
                        Post
                    </button>
                </div>
            </form>
        </div>
        {{-- /group-message-form-card --}}
        @endif

        {{-- Two-column: feed + sidebar --}}
        <div class="flex flex-col lg:flex-row gap-4">

            {{-- ── Message feed ── --}}
            <div class="flex-1 min-w-0 space-y-4">

                {{-- ── Message Feed ── --}}
                @if($messages->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-10 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <p class="text-sm font-medium text-gray-500">No messages yet</p>
                    <p class="text-xs text-gray-400 mt-1">Be the first to send a message to this group.</p>
                </div>
                @else
                @foreach($messages as $msg)

                {{-- ── Facebook-style Post Card ── --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

                    {{-- Header: Avatar + Name + Time + Menu --}}
                    <div class="flex items-center justify-between px-4 pt-4 pb-2">
                        <div class="flex items-center gap-3">
                            {{-- Avatar --}}
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex-shrink-0 overflow-hidden flex items-center justify-center ring-2 ring-indigo-100">
                                @if($msg->user && $msg->user->userprofile && $msg->user->userprofile->AvatarPath)
                                <img src="{{ $msg->user->userprofile->AvatarPath }}" alt="" class="w-full h-full object-cover">
                                @else
                                <svg class="w-5 h-5 text-indigo-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z" />
                                </svg>
                                @endif
                            </div>
                            {{-- Name + time --}}
                            <div>
                                <p class="text-sm font-semibold text-gray-900 leading-tight">
                                    {{ $msg->user->FullName ?? 'Unknown' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $msg->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        {{-- Three-dot menu (UI only) --}}
                        <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="5" cy="12" r="2" />
                                <circle cx="12" cy="12" r="2" />
                                <circle cx="19" cy="12" r="2" />
                            </svg>
                        </button>
                    </div>

                    {{-- Post text --}}
                    <div class="px-4 pb-3">
                        @if($msg->title)
                        <p class="text-sm font-semibold text-gray-800 mb-1">{{ $msg->title }}</p>
                        @endif
                        <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-line">{{ $msg->message }}</p>
                    </div>

                    {{-- Full-width image --}}
                    @if($msg->attachments && $msg->attachment_type === 'image')
                    <div class="w-full bg-gray-100">
                        <img src="{{ asset('storage/' . $msg->attachments) }}"
                            alt="Post image"
                            class="w-full max-h-[480px] object-cover cursor-pointer hover:opacity-95 transition">
                    </div>
                    @elseif($msg->attachments && $msg->attachment_type === 'video')
                    <div class="w-full bg-black">
                        <video src="{{ asset('storage/' . $msg->attachments) }}"
                            controls class="w-full max-h-[480px]"></video>
                    </div>
                    @endif

                    {{-- bottom padding --}}
                    <div class="pb-2"></div>

                </div>{{-- /post card --}}
                @endforeach

                @if($messages->hasPages())
                <div class="mt-2">{{ $messages->links() }}</div>
                @endif
                @endif
            </div>{{-- /main feed --}}

            {{-- ── Sidebar ── --}}
            <!-- <div class="w-full lg:w-72 xl:w-80 flex-shrink-0 space-y-4">

                {{-- About card --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                    <h3 class="text-sm font-bold text-gray-800 mb-3">About</h3>
                    @if($group->description)
                    <p class="text-sm text-gray-600 leading-relaxed mb-3">{{ $group->description }}</p>
                    @else
                    <p class="text-xs text-gray-400 italic mb-3">No description provided.</p>
                    @endif
                    <div class="space-y-2 text-xs text-gray-500">
                        @if($group->groupCategory)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span>Category: <span class="font-medium text-gray-700">{{ $group->groupCategory->name }}</span></span>
                        </div>
                        @endif
                        @if($group->group_type)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>Type: <span class="font-medium text-gray-700">{{ ucfirst($group->group_type) }}</span></span>
                        </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197" />
                            </svg>
                            <span><span class="font-medium text-gray-700">{{ $totalMembers }}</span> member{{ $totalMembers != 1 ? 's' : '' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                            <span>Your role:
                                <span class="font-medium {{ $isAdmin ? 'text-amber-700' : 'text-green-700' }}">
                                    {{ $isAdmin ? 'Group Admin' : ucfirst($grouplist->role) }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Members preview --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-bold text-gray-800">Members</h3>
                        <button onclick="activateTab('members')"
                            class="text-xs text-indigo-600 hover:underline font-medium">See all</button>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($grouplinks->getCollection()->take(12) as $gl)
                        @php $mu = $gl->user; $mp = $mu ? $mu->userprofile : null; @endphp
                        @if($mu)
                        <div class="w-9 h-9 rounded-full overflow-hidden border-2 border-white shadow-sm bg-indigo-50 flex items-center justify-center"
                            title="{{ $mu->FullName }}">
                            @if($mp && $mp->AvatarPath)
                            <img src="{{ $mp->AvatarPath }}" alt="{{ $mu->FullName }}" class="w-full h-full object-cover">
                            @else
                            <span class="text-xs font-bold text-indigo-600">{{ strtoupper(substr($mu->FullName,0,1)) }}</span>
                            @endif
                        </div>
                        @endif
                        @endforeach
                        @if($totalMembers > 12)
                        <div class="w-9 h-9 rounded-full bg-gray-100 border-2 border-white shadow-sm flex items-center justify-center text-xs font-semibold text-gray-500">
                            +{{ $totalMembers - 12 }}
                        </div>
                        @endif
                    </div>
                </div>

            </div>{{-- /sidebar --}} -->
        </div>
    </div>{{-- /tab-discussion --}}

    {{-- ── MEMBERS TAB ─────────────────────────────────────────────── --}}
    <div id="tab-members" class="tab-panel hidden">

        {{-- Header row --}}
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-semibold text-gray-700">
                {{ $totalMembers }} Member{{ $totalMembers != 1 ? 's' : '' }}
            </p>
        </div>

        @if($grouplinks->isEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl p-12 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <p class="text-sm font-medium text-gray-500">No members yet.</p>
        </div>
        @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($grouplinks as $gl)
            @php
            $u = $gl->user;
            $p = $u ? $u->userprofile : null;
            $nm = $u ? $u->FullName : 'Unknown';
            $av = $p ? $p->AvatarPath : null;
            $isGroupAdmin = $gl->role === 'group_admin';
            @endphp
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition group">
                {{-- Avatar block --}}
                <div class="relative bg-gradient-to-br from-indigo-50 to-purple-50 h-28 flex items-center justify-center">
                    <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-white shadow-md bg-indigo-100 flex items-center justify-center">
                        @if($av)
                        <img src="{{ $av }}" alt="{{ $nm }}" class="w-full h-full object-cover">
                        @else
                        <span class="text-2xl font-bold text-indigo-500">{{ strtoupper(substr($nm,0,1)) }}</span>
                        @endif
                    </div>
                    {{-- Admin crown badge --}}
                    @if($isGroupAdmin)
                    <span class="absolute top-2 right-2 bg-amber-400 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                        Admin
                    </span>
                    @endif
                </div>
                {{-- Info block --}}
                <div class="px-3 py-3 text-center">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $nm }}</p>
                    @if($u)
                    <p class="text-xs text-gray-400 truncate mt-0.5">{{ $u->email }}</p>
                    @endif
                    <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $isGroupAdmin ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }}">
                        {{ $isGroupAdmin ? 'Group Admin' : ucfirst($gl->role) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        @if($grouplinks->hasPages())
        <div class="mt-4">{{ $grouplinks->links() }}</div>
        @endif
        @endif
    </div>

    {{-- ── ABOUT TAB ───────────────────────────────────────────────── --}}
    <div id="tab-about" class="tab-panel hidden">
        <div class="max-w-2xl space-y-4">

            {{-- Stats row --}}
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-indigo-600">{{ $totalMembers }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Members</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-indigo-600">{{ $messages->total() }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Posts</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-indigo-600">{{ $isAdmin ? '★' : '✓' }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $isAdmin ? 'Admin' : 'Member' }}</p>
                </div>
            </div>

            {{-- Main info card --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm divide-y divide-gray-100">

                {{-- Description --}}
                <div class="p-5">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">About</h3>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        {{ $group->description ?: 'No description provided for this group.' }}
                    </p>
                </div>

                {{-- Info rows --}}
                <div class="p-5 space-y-4">

                    {{-- Group name --}}
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-indigo-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Group Name</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $group->name }}</p>
                        </div>
                    </div>

                    {{-- Category --}}
                    @if($group->groupCategory)
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-purple-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Category</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $group->groupCategory->name }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Group type --}}
                    @if($group->group_type)
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-green-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Group Type</p>
                            <p class="text-sm font-semibold text-gray-800">{{ ucfirst($group->group_type) }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Your role --}}
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-amber-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Your Role</p>
                            <span class="inline-block mt-0.5 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                {{ $isAdmin ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }}">
                                {{ $isAdmin ? 'Group Admin' : ucfirst($grouplist->role) }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>{{-- /tab panels --}}
@push('styles')

<style>
    /* Tab nav */
    .tab-btn.active-tab {
        border-color: #4f46e5;
        color: #4f46e5;
        font-weight: 600;
    }

    /* Mode pill: active = indigo filled, inactive = gray outline */
    .gf-mode-pill input:checked~span {
        background-color: #eef2ff;
        border-color: #4f46e5;
        color: #4338ca;
    }

    .gf-mode-pill input:not(:checked)~span {
        background-color: #f9fafb;
        border-color: #e5e7eb;
        color: #6b7280;
    }

    .gf-mode-pill span {
        display: inline-flex;
    }
</style>
@endpush

@push('scripts')

<script>
    // ── Tab switching ─────────────────────────────────────────────
    function activateTab(name) {
        // Hide all panels using style.display (avoids Tailwind specificity issues)
        document.querySelectorAll('.tab-panel').forEach(function(p) {
            p.style.display = 'none';
        });

        // Show target panel
        var panel = document.getElementById('tab-' + name);
        if (panel) panel.style.display = 'block';

        // Update tab button styles
        document.querySelectorAll('.tab-btn').forEach(function(b) {
            b.classList.remove('active-tab');
        });
        var btn = document.querySelector('.tab-btn[data-tab="' + name + '"]');
        if (btn) btn.classList.add('active-tab');

        // Update URL hash without scrolling
        history.replaceState(null, '', '#' + name);
    }

    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            activateTab(this.dataset.tab);
        });
    });

    // Activate initial tab on page load
    (function() {
        var hash = location.hash.replace('#', '');
        var valid = ['discussion', 'members', 'about'];
        activateTab(valid.indexOf(hash) !== -1 ? hash : 'discussion');
    })();

    // (mode pills removed — no syncModePills needed)

    // ── Character counter ─────────────────────────────────────────
    document.getElementById('gf-message').addEventListener('input', function() {
        document.getElementById('gf-char-count').textContent = this.value.length;
    });

    // ── File trigger (Photo / Video / File buttons) ───────────────
    function triggerFile(accept) {
        var input = document.getElementById('gf-file-input');
        input.accept = accept;
        input.click();
    }

    function handleFileSelect(input) {
        if (!input.files || input.files.length === 0) return;
        var file = input.files[0];
        var area = document.getElementById('gf-preview-area');
        var img = document.getElementById('gf-img-preview');
        var vid = document.getElementById('gf-vid-preview');
        var fdiv = document.getElementById('gf-file-preview');
        var fname = document.getElementById('gf-file-name');

        // Hide all previews first
        img.classList.add('hidden');
        vid.classList.add('hidden');
        fdiv.classList.add('hidden');

        if (file.type.startsWith('image/')) {
            img.src = URL.createObjectURL(file);
            img.classList.remove('hidden');
        } else if (file.type.startsWith('video/')) {
            vid.src = URL.createObjectURL(file);
            vid.classList.remove('hidden');
        } else {
            fname.textContent = file.name;
            fdiv.classList.remove('hidden');
        }
        area.classList.remove('hidden');
    }

    function clearAttachment() {
        document.getElementById('gf-file-input').value = '';
        document.getElementById('gf-preview-area').classList.add('hidden');
        document.getElementById('gf-img-preview').src = '';
        document.getElementById('gf-vid-preview').src = '';
    }

    // ── Schedule toggle ───────────────────────────────────────────
    function toggleSchedule() {
        var box = document.getElementById('gf-execute-at');
        var hidden = box.classList.toggle('hidden');
        document.getElementById('gf-send-later-val').value = hidden ? 'false' : 'true';
    }

    function clearSchedule() {
        document.getElementById('gf-execute-at').classList.add('hidden');
        document.getElementById('gf-send-later-val').value = 'false';
    }

    // ── Focus form (header Send Message button) ───────────────────
    function focusMessageForm() {
        activateTab('discussion');
        var card = document.getElementById('group-message-form-card');
        if (card) {
            card.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            setTimeout(function() {
                document.getElementById('gf-message').focus();
            }, 400);
        }
    }

    // ── Leave Group ───────────────────────────────────────────────
    function confirmRemoveGroup(btn) {
        var id = btn.dataset.groupId,
            name = btn.dataset.groupName;
        if (confirm('Are you sure you want to leave "' + name + '"?\nThis action cannot be undone.')) {
            document.getElementById('remove-form-' + id).submit();
        }
    }

    // ── Submit ────────────────────────────────────────────────────
    function submitGroupMessage() {
        var form = document.getElementById('group-msg-form');
        var btn = document.getElementById('gf-submit-btn');
        var errBox = document.getElementById('gf-error');
        errBox.classList.add('hidden');

        var fd = new FormData(form);
        // send_later is controlled by hidden input, not a checkbox
        btn.disabled = true;
        btn.textContent = 'Posting…';

        fetch('{{ $sendUrl }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: fd
            })
            .then(function(r) {
                return r.json();
            })
            .then(function(data) {
                if (data.success) {
                    form.reset();
                    clearAttachment();
                    alert('Post Successfully');
                    document.getElementById('gf-char-count').textContent = '0';

                    // Redirect with ?posted=1 so success toast shows after reload
                    window.location.href = window.location.pathname + '?posted=1#discussion';
                } else if (data.errors) {
                    var msgs = Object.values(data.errors).flat().join(' ');
                    errBox.textContent = msgs;
                    errBox.classList.remove('hidden');
                    errBox.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                } else {
                    errBox.textContent = 'Something went wrong. Please try again.';
                    errBox.classList.remove('hidden');
                }
            })
            .catch(function() {
                alert("facil");
                errBox.textContent = 'Network error. Please try again.';
                errBox.classList.remove('hidden');
            })
            .finally(function() {
                btn.disabled = false;
                btn.textContent = 'Post';
            });
    }

    // ── Success Swal: check ?posted=1 in URL ─────────────────────
    if (new URLSearchParams(window.location.search).get('posted') === '1') {
        history.replaceState(null, '', window.location.pathname + '#discussion');

        Swal.fire({
            icon: 'success',
            title: 'Posted!',
            text: 'Your post has been created successfully.',
            timer: 2500,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }
</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@endsection