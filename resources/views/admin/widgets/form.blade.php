@extends('layouts.admin.layout')
@section('content')
<div class="w-full max-w-4xl">

    <h1 class="admin-h1 mb-6 flex items-center gap-3">
        <a href="{{ url('/admin/widgets') }}" title="Back"
            class="rounded-full bg-gray-100 hover:bg-gray-200 p-2 transition">
            <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
        </a>
        Add Widget
    </h1>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">

        <div class="px-6 py-4 border-b border-gray-100">
            <p class="text-sm text-gray-500">Enter the HTML / CSS / JS content for this widget. It will be rendered on the public site.</p>
        </div>

        <form method="post" action="{{ url('/admin/widgets/create') }}" id="widgets" enctype="multipart/form-data">
            @csrf

            @if (old('content') != '')
            @php $content = old('content'); @endphp
            @endif

            <div class="px-6 pt-5 pb-2 grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Page</label>
                    <select name="page" id="page-select" class="tw-form-control w-full">
                        <option value="">Select Page</option>
                        <option value="home" {{ old('page') === 'home' ? 'selected' : '' }}>Home</option>
                        <option value="contact" {{ old('page') === 'contact' ? 'selected' : '' }}>Contact</option>
                        <option value="faq" {{ old('page') === 'faq' ? 'selected' : '' }}>Faq</option>
                        <option value="post" {{ old('page') === 'post' ? 'selected' : '' }}>Post</option>
                    </select>
                    @if($errors->has('page'))
                    <p class="mt-1.5 text-xs text-red-600">{{ $errors->first('page') }}</p>
                    @endif
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Display Order</label>
                    <input type="number" name="display_order" value="{{ old('display_order', 0) }}" min="0" class="tw-form-control w-full">
                </div>
            </div>

            <div class="px-6 pb-2" id="position-field" style="display:none">
                <div class="max-w-xs">
                    <label class="text-sm font-medium text-gray-700 block mb-1">Position</label>
                    <select name="position" class="tw-form-control w-full">
                        <option value="top" {{ old('position') === 'top' ? 'selected' : '' }}>Top</option>
                        <option value="bottom" {{ old('position') === 'bottom' ? 'selected' : '' }}>Bottom</option>
                    </select>
                </div>
            </div>

            <div class="px-6 pt-3 pb-6">

                {{-- Editor label + badge --}}
                <div class="flex items-center justify-between mb-2">
                    <label for="content" class="text-sm font-medium text-gray-700">Content</label>
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 rounded px-2 py-0.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                        HTML / CSS / JS
                    </span>
                </div>

                {{-- Editor wrapper --}}
                <div class="rounded-lg overflow-hidden border border-gray-300 shadow-inner">
                    {{-- Editor toolbar --}}
                    <div class="flex items-center gap-2 px-3 py-2 bg-gray-800 border-b border-gray-700">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                        <span class="w-3 h-3 rounded-full bg-green-500"></span>
                        <span class="ml-3 text-xs text-gray-400 font-mono">widget.html</span>
                    </div>
                    <textarea name="content" id="content">{{ $content ?? '' }}</textarea>
                </div>

                @if($errors->has('content'))
                <p class="mt-1.5 text-xs text-red-600">{{ $errors->first('content') }}</p>
                @endif

            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-lg flex items-center gap-3">
                <button type="submit" id="submit"
                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">
                    Save Widget
                </button>
                <a href="{{ url('/admin/widgets') }}"
                    class="px-5 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<link href="{{ url('css/code_mirror/codemirror.css') }}" rel="stylesheet">
<link href="{{ url('css/code_mirror/material.css') }}" rel="stylesheet">
<script src="{{ asset('js/code_mirror/codemirror.js') }}"></script>
<script src="{{ asset('js/code_mirror/css.js') }}"></script>
<script src="{{ asset('js/code_mirror/htmlmixed.js') }}"></script>
<script src="{{ asset('js/code_mirror/javascript.js') }}"></script>
<script src="{{ asset('js/code_mirror/xml.js') }}"></script>
<style>
    .CodeMirror {
        height: 420px;
        font-size: 13px;
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
    }
</style>
<script>
    var htmlEditor = CodeMirror.fromTextArea(document.getElementById('content'), {
        lineNumbers: true,
        mode: 'htmlmixed',
        theme: 'material',
        indentUnit: 4,
        tabSize: 4,
        indentWithTabs: false,
        lineWrapping: false,
        autoCloseTags: true,
        extraKeys: {
            'Ctrl-Space': 'autocomplete'
        },
    });
    htmlEditor.setSize('100%', 420);

    (function() {
        var pageSelect = document.getElementById('page-select');
        var positionField = document.getElementById('position-field');

        function togglePosition() {
            positionField.style.display = pageSelect.value === 'contact' || pageSelect.value === 'faq' || pageSelect.value === 'post' ? 'block' : 'none';
        }

        pageSelect.addEventListener('change', togglePosition);
        togglePosition();
    })();
</script>
@endpush