@extends('layouts.admin.layout')
@section('content')
<div class="relative admin-page-wrapper overflow-x-hidden">

    {{-- ── Page Header ──────────────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-center justify-between mb-6">
        <div>
            <h1 class="admin-h1">Code Snippets</h1>
            <p class="text-sm text-gray-500 mt-1">Define reusable HTML blocks once, embed them anywhere with a tag.</p>
        </div>
        <a href="{{ url('/admin/widgets/create') }}"
            class="no-underline inline-flex items-center gap-2 text-white px-4 py-2 custom-green rounded shadow text-sm font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current" viewBox="0 0 409.6 409.6">
                <path d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067 C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067 s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"/>
            </svg>
            Add Snippet
        </a>
    </div>

    <div class="my-3">
        @include('partials.message')

        @if (count($getWidgets) > 0)

            @php
                $i = ($getWidgets->currentPage() - 1) * $getWidgets->perPage() + 1;
            @endphp

            @foreach ($getWidgets as $widgetData)

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-5 overflow-hidden">

                    {{-- Card Header --}}
                    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center gap-2 min-w-0">
                            {{-- Snippet icon --}}
                            <span class="flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded bg-indigo-100 text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                </svg>
                            </span>
                            <span class="text-sm font-semibold text-gray-700 truncate">{{ $widgetData->slug }}</span>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0 ml-4">
                            <span class="text-xs text-gray-400">
                                {{ $widgetData->created_at->diffForHumans() }}
                                @if (isset($widgetData->userInfo->name))
                                    &middot; {{ $widgetData->userInfo->name }}
                                @endif
                            </span>
                            <a href="{{ url('admin/widgets/' . $widgetData->id . '/edit') }}"
                                class="btn btn-primary submit-btn text-white rounded px-3 py-1 text-xs font-medium">Edit</a>
                        </div>
                    </div>

                    {{-- Embed Tag Row --}}
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-1">Embed tag — copy and paste into any page</p>
                        <div class="flex items-stretch rounded overflow-hidden border border-gray-300">
                            <input type="text" name="slug" readonly
                                class="flex-1 min-w-0 px-3 py-2 text-sm text-indigo-700 font-mono bg-gray-50 border-0 outline-none"
                                id="slug_{{ $widgetData->id }}"
                                value="<app-widgets [widgetID]='{{ $widgetData->slug }}'></app-widgets>">
                            <button onclick="copyClip('{{ $widgetData->id }}')"
                                class="inline-flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold px-4 border-l border-yellow-600 transition-colors"
                                id="copy-btn-{{ $widgetData->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Copy
                            </button>
                        </div>
                    </div>

                    {{-- Code Preview --}}
                    <div class="px-4 py-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-2">HTML Content</p>
                        <div class="rounded overflow-x-auto border border-gray-200">
                            <textarea name="content" class="tw-form-control w-full"
                                id="content_{{ $widgetData->id }}">{{ $widgetData->content }}</textarea>
                        </div>
                    </div>

                </div>
            @endforeach

        @else
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                </svg>
                <p class="text-gray-500 font-medium">No snippets yet</p>
                <p class="text-sm text-gray-400 mt-1">Create your first reusable code snippet.</p>
                <a href="{{ url('/admin/widgets/create') }}" class="mt-4 inline-block custom-green text-white text-sm px-4 py-2 rounded">Add Snippet</a>
            </div>
        @endif

        {{ $getWidgets->links('layouts.pagination', ['search' => $build]) }}
    </div>
</div>
@endsection

@push('scripts')
    <link href="{{ url('css/code_mirror/codemirror.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('css/code_mirror/material.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('js/code_mirror/codemirror.js') }}"></script>
    <script src="{{ asset('js/code_mirror/css.js') }}"></script>
    <script src="{{ asset('js/code_mirror/htmlmixed.js') }}"></script>
    <script src="{{ asset('js/code_mirror/javascript.js') }}"></script>
    <script src="{{ asset('js/code_mirror/xml.js') }}"></script>
    <script type="text/javascript">
        @if (count($getWidgets) > 0)
            @foreach ($getWidgets as $widgetData)
                editor('{{ $widgetData->id }}')
            @endforeach
        @endif

        function editor(id) {
            CodeMirror.fromTextArea(document.getElementById("content_" + id), {
                lineNumbers: true,
                mode: 'htmlmixed',
                theme: 'material',
                readOnly: true,
                lineWrapping: true,
            });
        }

        function copyClip(id) {
            var copyText = document.getElementById("slug_" + id);
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");

            var btn = document.getElementById("copy-btn-" + id);
            var original = btn.innerHTML;
            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Copied!';
            btn.classList.replace('bg-yellow-500', 'bg-green-500');
            btn.classList.replace('border-yellow-600', 'border-green-600');
            setTimeout(function() {
                btn.innerHTML = original;
                btn.classList.replace('bg-green-500', 'bg-yellow-500');
                btn.classList.replace('border-green-600', 'border-yellow-600');
            }, 2000);
        }
    </script>
@endpush
