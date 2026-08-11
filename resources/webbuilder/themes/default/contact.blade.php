@extends('theme::layout')

@section('title', 'Contact Us')

@section('content')
@include('theme::_hero_banner', [
'heroTitle' => 'Contact Us',
'heroSubtitle' => 'We\'d love to hear from you — get in touch with our parish',
'breadcrumbs' => [
['label' => 'Home', 'url' => route('web.home')],
['label' => 'Contact Us'],
],
])
@if($topwidget->isNotEmpty())
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
    @foreach($topwidget as $widget)
        {!! $widget->content !!}
    @endforeach
</div>
@endif

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="max-w-lg mx-auto">

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

            @if(config('settings.contact_captcha_status')=="1")
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
            @error('g-recaptcha-response')
            <p class="text-red-500 text-xs mb-3">{{ $message }}</p>
            @enderror
            @endif

            <div>
                <button type="submit"
                    class="w-full py-3 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700 transition">
                    Send Message
                </button>
            </div>
        </form>
    </div>{{-- max-w-lg --}}
</div>{{-- max-w-6xl --}}

@if($bottomwidget->isNotEmpty())
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
    @foreach($bottomwidget as $widget)
        {!! $widget->content !!}
    @endforeach
</div>
@endif

@endsection

@push('scripts')
@php
//dd(config('settings.contact_captcha_status'));
$recaptchaKey = env('GOOGLE_RECAPTCHA_KEY', ''); @endphp
@if($recaptchaKey && config('settings.contact_captcha_status')=="1")
<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaKey }}"></script>
<script>
    grecaptcha.ready(function() {
        var form = document.getElementById('guest-register-form');
        var siteKey = form.dataset.sitekey;
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            grecaptcha.execute(siteKey, {
                action: 'guest_register'
            }).then(function(token) {
                document.getElementById('g-recaptcha-response').value = token;
                form.submit();
            });
        });
    });
</script>
@endif
@endpush