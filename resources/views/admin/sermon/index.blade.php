@extends('layouts.admin.layout')

@section('content')
    @include('partials.message')
    <div class="flex lg:items-center md:items-center justify-between flex-col lg:flex-row md:flex-row">
        <h1 class="admin-h1">Sermon</h1>
        <div class="flex items-center gap-3">
            <a href="{{ url('/admin/sermon/create') }}" class="btn-sm blue-bg text-white rounded px-4 py-1 text-sm font-medium no-underline">+ Add Sermon</a>
            <form method="GET" action="{{ url('/admin/sermons') }}" role="search" enctype="multipart/form-data"
                class="mb-0">
                <div class="flex lg:justify-end md:justify-end items-center">
                    <div class="search relative w-48">
                        <input type="text" name="q" class="tw-form-control w-full relative" placeholder="Search"
                            value="{{ old('q') }}">
                        <button class="no-underline text-white px-4 mx-1 py-1 absolute right-0 focus:outline-none">
                            <img src="{{ url('uploads/icons/search.svg') }}"
                                class="w-4 h-4 absolute right-0 mt-2 mx-1 top-0">
                        </button>
                    </div>
                    <div class="date-select date-select_none dashboard-reset mx-1 lg:mx-0 md:mx-0">
                        <a href="{{ url('/admin/sermons') }}" id="do-reset"
                            class="text-sm border bg-gray-100 text-grey-darkest py-1 px-4">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="custom-table bg-white my-3 shadow">
        <table class="w-full">
            <thead class="bg-grey-light">
                <tr class="border-t-2 border-b-2">
                    <th class="text-left text-sm px-3 py-3 text-grey-darker w-16"></th>
                    <th class="text-left text-sm px-3 py-3 text-grey-darker">Title</th>
                    <th class="text-left text-sm px-3 py-3 text-grey-darker">Author</th>
                    <th class="text-left text-sm px-3 py-3 text-grey-darker text-center">Chapters</th>
                    <th class="text-left text-sm px-3 py-3 text-grey-darker text-center">Likes / Dislikes</th>
                    <th class="text-left text-sm px-3 py-3 text-grey-darker text-center">Status</th>
                    <th class="text-left text-sm px-3 py-3 text-grey-darker">Added On</th>
                    <th class="text-left text-sm px-3 py-3 text-grey-darker text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sermons as $sermon)
                    <tr class="border-b hover:bg-gray-50">
                        {{-- Cover thumbnail --}}
                        <td class="px-3 py-2">
                            <img src="{{ $sermon->CoverImagePath }}" alt="{{ $sermon->title }}"
                                class="w-6 h-6 object-cover rounded">
                        </td>

                        {{-- Title + description --}}
                        <td class="px-3 py-2 max-w-xs">
                            <a href="{{ url('/admin/sermon/show/' . $sermon->id) }}"
                               class="font-semibold text-sm text-gray-800 hover:underline capitalize">
                                {{ $sermon->title }}
                            </a>
                            <p class="text-xs text-gray-500 mt-1">{{ \Str::limit($sermon->description, 60, '...') }}</p>
                        </td>

                        {{-- Author --}}
                        <td class="px-3 py-2 text-sm text-gray-700 whitespace-nowrap">
                            {{ $sermon->user->FullName }}
                        </td>

                        {{-- Chapters count --}}
                        <td class="px-3 py-2 text-sm text-gray-700 text-center">
                            <a href="{{ url('/admin/links/' . $sermon->id) }}"
                               class="inline-block bg-blue-50 text-blue-700 rounded px-2 py-0.5 text-xs font-medium hover:underline">
                                {{ count($sermon->sermonlinks) }}
                            </a>
                        </td>

                        {{-- Likes / Dislikes --}}
                        <td class="px-3 py-2 text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 text-xs text-gray-600 mr-2">
                                <img src="{{ url('uploads/icons/like.svg') }}" class="w-3 h-3">
                                {{ $sermon->sermonlikevote }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs text-gray-600">
                                <img src="{{ url('uploads/icons/dislike.svg') }}" class="w-3 h-3">
                                {{ $sermon->sermonunlikevote }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-3 py-2 text-center">
                            <span class="text-xs bg-green-100 text-green-700 rounded px-2 py-0.5 font-medium">Published</span>
                        </td>

                        {{-- Added On --}}
                        <td class="px-3 py-2 text-xs text-gray-600 whitespace-nowrap">
                            {{ $sermon->created_at->format('d M Y') }}
                        </td>

                        {{-- Actions --}}
                        <td class="px-3 py-2 text-center whitespace-nowrap">
                            <a href="{{ url('/admin/sermon/show/' . $sermon->id) }}"
                               class="text-xs text-gray-600 hover:underline mr-2">View</a>
                            <a href="{{ url('/admin/sermon/edit/' . $sermon->id) }}"
                               class="text-xs text-blue-600 hover:underline mr-2">Edit</a>
                            <form action="{{ url('/admin/sermon/delete/' . $sermon->id) }}" method="POST"
                                  class="inline mb-0"
                                  onsubmit="return confirm('Delete this sermon and all its chapters?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-xs text-red-600 hover:underline bg-transparent border-0 cursor-pointer p-0">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-3 py-6 text-center text-gray-500 text-sm">No Records Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $sermons->links() }}
@endsection

