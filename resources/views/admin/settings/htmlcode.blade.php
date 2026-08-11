@extends('layouts.admin.layout')
@section('content')
<div class="w-full flex flex-col">
<h1 class="text-xl font-semibold mb-6 text-gray-700">Settings</h1>
<div class="w-full main-content bg-white flex h-auto">
    <div class="flex flex-col lg:flex-row w-full">
        @include('layouts.admin.settingsbar')
        <div class="flex-1 px-8 py-6 min-w-0">
            @include('partials._page_header', ['pageTitle' => 'HTML / JS Code'])
            @include('partials.message')

            <form method="POST" action="{{ url('/admin/settings/htmlcode') }}">
                @csrf
                <div class="bg-white border border-gray-200 rounded shadow-sm mb-6 max-w-3xl">
                    <div class="px-6 py-3 border-b border-gray-100 bg-gray-50">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Header Code</p>
                    </div>
                    <div class="px-6 py-5">
                        <p class="text-xs text-gray-400 mb-3">Paste scripts here that should load in the <code>&lt;head&gt;</code> section — e.g. Google Analytics, Google Search Console verification, or any other tracking/verification tags.</p>
                        <textarea name="header_code" id="header_code" rows="8"
                            class="tw-form-control w-full font-mono text-sm"
                            placeholder="<!-- e.g. Google Analytics or site verification meta tags -->"
                        >{{ old('header_code', \Config::get('settings.header_code')) }}</textarea>
                        <span class="text-red-500 text-xs block mt-1">{{ $errors->first('header_code') }}</span>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded shadow-sm mb-6 max-w-3xl">
                    <div class="px-6 py-3 border-b border-gray-100 bg-gray-50">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Footer Code</p>
                    </div>
                    <div class="px-6 py-5">
                        <p class="text-xs text-gray-400 mb-3">Paste scripts here that should load just before the closing <code>&lt;/body&gt;</code> tag — e.g. chat widgets, remarketing pixels, or any deferred JavaScript.</p>
                        <textarea name="footer_code" id="footer_code" rows="8"
                            class="tw-form-control w-full font-mono text-sm"
                            placeholder="<!-- e.g. Facebook Pixel, live chat scripts -->"
                        >{{ old('footer_code', \Config::get('settings.footer_code')) }}</textarea>
                        <span class="text-red-500 text-xs block mt-1">{{ $errors->first('footer_code') }}</span>
                    </div>
                </div>

                <div class="mb-8">
                    <input type="submit" value="Save Changes" name="submit" class="btn btn-primary submit-btn cursor-pointer">
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
