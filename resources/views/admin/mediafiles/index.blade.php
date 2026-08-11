@extends('layouts.admin.layout')

@section('content')
@php
$addRoutes = [
'image' => url('/admin/mediafile/image/create'),
'audio' => url('/admin/mediafile/audio/create'),
'video' => url('/admin/mediafile/video/create'),
];
$addLabels = ['image' => 'Add Image', 'audio' => 'Add Audio', 'video' => 'Add Video'];
$addIcons = ['image' => 'fa-image', 'audio' => 'fa-music', 'video' => 'fa-video'];
$isAdmin = auth()->user()->usergroup_id == 3;
@endphp

{{-- ── Header ─────────────────────────────────────────────────────────── --}}
<div class="flex items-center justify-between my-3">
    <h1 class="admin-h1">Media Library <span class="text-gray-400 text-base font-normal">({{ $count }})</span></h1>
    @if($isAdmin || Auth::user()->hasPermission('create-files'))
    <a href="{{ $addRoutes[$type] }}"
        class="text-sm rounded px-3 py-1.5 flex items-center gap-2 btn btn-primary submit-btn">
        <i class="fas {{ $addIcons[$type] }} text-xs"></i>
        {{ $addLabels[$type] }}
    </a>
    @endif
</div>

@include('partials.message')

<div class="bg-white shadow px-4 py-5">

    {{-- ── Type tabs ───────────────────────────────────────────────────── --}}
    <div class="flex items-center gap-1 border-b border-gray-200 mb-5 flex-wrap">
        @foreach(['image' => ['label' => 'Images', 'icon' => 'fa-image'],
        'audio' => ['label' => 'Audio', 'icon' => 'fa-music'],
        'video' => ['label' => 'Videos', 'icon' => 'fa-video']] as $tab => $meta)
        <a href="{{ request()->fullUrlWithQuery(['type' => $tab, 'search' => '', 'page' => 1]) }}"
            class="flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition
                  {{ $type === $tab
                        ? 'border-blue-600 text-blue-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            <i class="fas {{ $meta['icon'] }} text-xs"></i>
            {{ $meta['label'] }}
        </a>
        @endforeach

        {{-- Search --}}
        <form method="GET" action="{{ url('/admin/mediafiles') }}" class="ml-auto flex items-center gap-2 pb-1">
            <input type="hidden" name="type" value="{{ $type }}">
            <div class="relative">
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Search…"
                    class="tw-form-control text-sm pr-8 py-1.5 w-48">
                <button type="submit"
                    class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-600">
                    <i class="fas fa-search text-xs"></i>
                </button>
            </div>
            @if($search)
            <a href="{{ url('/admin/mediafiles') . '?type=' . $type }}"
                class="text-sm text-gray-500 hover:text-gray-700">Reset</a>
            @endif
        </form>
    </div>

    {{-- ── Image grid ──────────────────────────────────────────────────── --}}
    @if($type === 'image')
    @if($files->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <i class="fas fa-images text-4xl mb-3 block"></i>
        <p class="text-sm">No images uploaded yet.</p>
        @if($isAdmin || Auth::user()->hasPermission('create-files'))
        <a href="{{ $addRoutes['image'] }}" class="text-sm text-blue-500 hover:underline mt-1 block">
            Upload your first image
        </a>
        @endif
    </div>
    @else
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
        @foreach($files as $file)
        <div class="group relative rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
            <button type="button" class="block w-full focus:outline-none"
                onclick="openMediaModal('image', {{ json_encode($file->name) }}, {{ json_encode($file->UrlPath) }}, {{ json_encode($file->description ?? '') }})">
                <img src="{{ $file->UrlPath }}" alt="{{ $file->name }}"
                    class="w-full h-28 object-cover transition group-hover:opacity-80">
            </button>
            <div class="px-2 py-1.5 flex items-center justify-between gap-1">
                <p class="text-xs text-gray-600 truncate flex-1" title="{{ $file->name }}">{{ $file->name }}</p>
                @if($isAdmin || Auth::user()->hasPermission('create-files'))
                <form action="{{ url('/admin/mediafile/delete/' . $file->id) }}" method="POST"
                    onsubmit="return confirm('Delete this image?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="flex-shrink-0 text-gray-300 hover:text-red-500 transition text-lg leading-none"
                        title="Delete">&times;</button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ── Audio / Video table ──────────────────────────────────────────── --}}
    @else
    @php $isAudio = $type === 'audio'; @endphp
    @if($files->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <i class="fas {{ $isAudio ? 'fa-music' : 'fa-video' }} text-4xl mb-3 block"></i>
        <p class="text-sm">No {{ $isAudio ? 'audio files' : 'videos' }} uploaded yet.</p>
        @if($isAdmin || Auth::user()->hasPermission('create-files'))
        <a href="{{ $addRoutes[$type] }}" class="text-sm text-blue-500 hover:underline mt-1 block">
            Upload your first {{ $isAudio ? 'audio file' : 'video' }}
        </a>
        @endif
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    <th class="px-4 py-3 text-left w-12"></th>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Description</th>
                    <th class="px-4 py-3 text-left">Added</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($files as $file)
                @php
                $fileUrl = ($file->type === 'url') ? $file->url : $file->UrlPath;
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center
                                {{ $isAudio ? 'bg-purple-100 text-purple-500' : 'bg-blue-100 text-blue-500' }}">
                            <i class="fas {{ $isAudio ? 'fa-music' : 'fa-video' }} text-sm"></i>
                        </div>
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $file->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ \Str::limit($file->description, 60) ?: '—' }}</td>
                    <td class="px-4 py-3 text-gray-400 text-xs whitespace-nowrap">
                        {{ $file->created_at->format('d M Y') }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <button type="button"
                                onclick="openMediaModal('{{ $type }}', {{ json_encode($file->name) }}, {{ json_encode($fileUrl) }}, {{ json_encode($file->description ?? '') }})"
                                class="text-xs px-2.5 py-1.5 rounded border border-gray-200 text-gray-600 hover:bg-gray-100 transition flex items-center gap-1">
                                <i class="fas {{ $isAudio ? 'fa-play' : 'fa-eye' }} text-xs"></i>
                                {{ $isAudio ? 'Play' : 'View' }}
                            </button>
                            <a href="{{ $fileUrl }}" download target="_blank"
                                class="text-xs px-2.5 py-1.5 rounded border border-gray-200 text-gray-600 hover:bg-gray-100 transition"
                                title="Download">
                                <i class="fas fa-download text-xs"></i>
                            </a>
                            @if($isAdmin || Auth::user()->hasPermission('create-files'))
                            <form action="{{ url('/admin/mediafile/delete/' . $file->id) }}" method="POST"
                                onsubmit="return confirm('Delete this file?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="text-xs px-2.5 py-1.5 rounded border border-red-200 text-red-500 hover:bg-red-50 transition"
                                    title="Delete">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    @endif

    {{-- ── Pagination ──────────────────────────────────────────────────── --}}
    @if($files->hasPages())
    <div class="mt-5">{{ $files->links() }}</div>
    @endif

</div>

{{-- ── View / Play modal ───────────────────────────────────────────────── --}}
<div id="media-modal"
    class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4"
    onclick="if(event.target===this) closeMediaModal()">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl flex flex-col" style="max-height:85vh">
        <div class="flex items-center justify-between px-5 py-4 border-b flex-shrink-0">
            <h2 id="mm-title" class="text-base font-semibold text-gray-800 truncate pr-4"></h2>
            <button onclick="closeMediaModal()"
                class="text-gray-400 hover:text-gray-600 text-2xl leading-none flex-shrink-0">&times;</button>
        </div>
        <div id="mm-body" class="flex-1 overflow-y-auto p-5"></div>
        <div id="mm-footer" class="hidden px-5 py-3 border-t flex-shrink-0">
            <p id="mm-desc" class="text-sm text-gray-500"></p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    (function() {
        function openMediaModal(type, name, url, desc) {
            var modal = document.getElementById('media-modal');
            var title = document.getElementById('mm-title');
            var body = document.getElementById('mm-body');
            var footer = document.getElementById('mm-footer');
            var descEl = document.getElementById('mm-desc');

            title.textContent = name;

            if (type === 'image') {
                body.innerHTML = '<img src="' + url + '" alt="' + name + '" class="w-full rounded">';
            } else if (type === 'audio') {
                body.innerHTML =
                    '<audio controls class="w-full mt-2">' +
                    '<source src="' + url + '">Your browser does not support audio.</audio>';
            } else {
                var yt = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s]+)/);
                var vm = url.match(/vimeo\.com\/(\d+)/);
                if (yt) {
                    body.innerHTML = '<div class="aspect-video"><iframe src="https://www.youtube.com/embed/' + yt[1] + '" class="w-full h-full rounded" allowfullscreen></iframe></div>';
                } else if (vm) {
                    body.innerHTML = '<div class="aspect-video"><iframe src="https://player.vimeo.com/video/' + vm[1] + '" class="w-full h-full rounded" allowfullscreen></iframe></div>';
                } else {
                    body.innerHTML =
                        '<video controls class="w-full rounded"><source src="' + url + '">Your browser does not support video.</video>';
                }
            }

            if (desc && desc.trim()) {
                descEl.textContent = desc;
                footer.classList.remove('hidden');
            } else {
                footer.classList.add('hidden');
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMediaModal() {
            var modal = document.getElementById('media-modal');
            document.getElementById('mm-body').innerHTML = ''; // stop playback
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        window.openMediaModal = openMediaModal;
        window.closeMediaModal = closeMediaModal;

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMediaModal();
        });
    })();
</script>
@endpush