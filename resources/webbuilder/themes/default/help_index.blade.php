@extends('theme::layout')

@section('title', 'Help Requests')

@section('content')
@include('theme::_hero_banner', [
    'heroTitle'    => 'Help Requests',
    'heroSubtitle' => 'Let the community know how they can support you',
    'breadcrumbs'  => [
        ['label' => 'Home', 'url' => route('web.home')],
        ['label' => 'Help Requests'],
    ],
])
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Submit Form --}}
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-12">
        <h2 class="text-xl font-semibold text-yellow-900 mb-4">Submit a Help Request</h2>

        @auth
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

        <form action="{{ route('web.help.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" required
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400">{{ old('description') }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Details <span class="text-red-500">*</span></label>
                <input type="text" name="contact_details" value="{{ old('contact_details') }}" required
                       placeholder="Phone, email or any preferred way to reach you"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>
            <button type="submit"
                    class="px-6 py-2 bg-yellow-600 text-white text-sm font-medium rounded-md hover:bg-yellow-700">
                Submit Request
            </button>
        </form>
        @else
        <div class="text-center py-4 space-y-3">
            <p class="text-yellow-800 text-sm">You need to be logged in to submit a help request.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('web.guest.register') }}"
                   class="px-5 py-2 rounded-lg font-semibold bg-yellow-600 text-white hover:bg-yellow-700 text-sm text-center">
                    Register Free
                </a>
                <a href="{{ route('web.guest.login') }}"
                   class="px-5 py-2 rounded-lg font-semibold border border-yellow-500 text-yellow-800 hover:bg-yellow-100 text-sm text-center">
                    Login
                </a>
            </div>
        </div>
        @endauth
    </div>

    {{-- Approved Requests --}}
    <h2 class="text-xl font-semibold text-gray-700 mb-5">Community Help Requests</h2>

    @forelse($requests as $item)
    <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-5 mb-4">
        <h3 class="font-semibold text-gray-800">{{ $item->title }}</h3>
        <p class="text-sm text-gray-600 mt-1">{{ $item->description }}</p>
        @if($item->contact_details)
            <p class="text-xs text-gray-400 mt-2">Contact: {{ $item->contact_details }}</p>
        @endif
        <span class="text-xs text-gray-400 block mt-2">{{ $item->created_at->diffForHumans() }}</span>
    </div>
    @empty
    <p class="text-gray-500">No help requests at this time.</p>
    @endforelse

    <div class="mt-8">
        {{ $requests->links() }}
    </div>
</div>
@endsection
