@extends('theme::layout')

@section('title', 'Prayer Requests')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-800 mb-10">Prayer Requests</h1>

    {{-- Submit Form --}}
    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6 mb-12">
        <h2 class="text-xl font-semibold text-indigo-800 mb-4">Submit a Prayer Request</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('web.prayer.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" required
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">{{ old('description') }}</textarea>
            </div>
            <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                Submit Request
            </button>
        </form>
    </div>

    {{-- Approved Requests --}}
    <h2 class="text-xl font-semibold text-gray-700 mb-5">Community Prayer Requests</h2>

    @forelse($requests as $item)
    <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-5 mb-4">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 text-xs font-bold">
                {{ $item->is_anonymous ? 'A' : strtoupper(substr(optional($item->user)->name ?? 'A', 0, 1)) }}
            </div>
            <span class="text-sm font-medium text-gray-600">
                {{ $item->is_anonymous ? 'Anonymous' : (optional($item->user)->name ?? 'Anonymous') }}
            </span>
            <span class="text-xs text-gray-400 ml-auto">{{ $item->created_at->diffForHumans() }}</span>
        </div>
        <h3 class="font-semibold text-gray-800">{{ $item->title }}</h3>
        <p class="text-sm text-gray-600 mt-1">{{ $item->description }}</p>
    </div>
    @empty
    <p class="text-gray-500">No prayer requests at this time.</p>
    @endforelse

    <div class="mt-8">
        {{ $requests->links() }}
    </div>
</div>
@endsection
