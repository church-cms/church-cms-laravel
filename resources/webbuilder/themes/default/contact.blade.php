@extends('theme::layout')

@section('title', 'Contact Us')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">Contact Us</h1>

    @if($_church)
    <div class="text-gray-500 text-sm mb-8">
        @if($_church->address)
            <p>&#128205; {{ $_church->address }}</p>
        @endif
    </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-4 rounded-lg mb-6 text-sm">
            &#10003; {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('web.contact.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
            <input type="text" name="fullname" value="{{ old('fullname') }}" required
                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
            <input type="text" name="mobile" value="{{ old('mobile') }}"
                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
            <textarea name="query" rows="5" required
                      class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">{{ old('query') }}</textarea>
        </div>

        <div>
            <button type="submit"
                    class="w-full py-3 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700 transition">
                Send Message
            </button>
        </div>
    </form>
</div>
@endsection
