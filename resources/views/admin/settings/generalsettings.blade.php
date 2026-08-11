@extends('layouts.admin.layout')
@section('content')
<div class="w-full flex flex-col">
    <h1 class="text-xl font-semibold mb-6 text-gray-700">Settings</h1>
    <div class="w-full main-content bg-white flex h-auto">

        <div class="flex flex-col lg:flex-row w-full">
            @include('layouts.admin.settingsbar')
            <div class="flex-1 px-8 py-6 min-w-0">
                @include('partials._page_header', ['pageTitle' => 'General Settings'])
                @include('partials.message')

                <form method="POST" action="{{ url('/admin/settings/generalsettings') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white border border-gray-200 rounded shadow-sm mb-6 max-w-3xl">
                        <table class="form-table w-full">
                            <tbody>

                                {{-- Church Full Name --}}
                                <tr class="border-b border-gray-100">
                                    <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                        <label for="church_full_name" class="text-sm font-medium text-gray-700 leading-8">Church Full Name</label>
                                    </th>
                                    <td class="py-4 px-6">
                                        <input type="text" name="church_full_name" id="church_full_name"
                                            class="tw-form-control w-full"
                                            placeholder="e.g. Grace Community Church of Chennai"
                                            value="{{ old('church_full_name', \Config::get('settings.church_full_name')) }}">
                                        <span class="text-red-500 text-xs block mt-1">{{ $errors->first('church_full_name') }}</span>
                                    </td>
                                </tr>

                                {{-- Church Short Name --}}
                                <tr class="border-b border-gray-100">
                                    <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                        <label for="church_short_name" class="text-sm font-medium text-gray-700 leading-8">Church Short Name</label>
                                    </th>
                                    <td class="py-4 px-6">
                                        <input type="text" name="church_short_name" id="church_short_name"
                                            class="tw-form-control w-full"
                                            placeholder="e.g. Grace Church"
                                            value="{{ old('church_short_name', \Config::get('settings.church_short_name')) }}">
                                        <span class="text-red-500 text-xs block mt-1">{{ $errors->first('church_short_name') }}</span>
                                    </td>
                                </tr>

                                {{-- Site Logo --}}
                                <tr class="border-b border-gray-100">
                                    <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                        <label class="text-sm font-medium text-gray-700 leading-8">Site Logo</label>
                                    </th>
                                    <td class="py-4 px-6">
                                        {{-- 360×90 preview area --}}
                                        <div class="mb-3" style="width:360px; height:90px;">
                                            @if(\Config::get('settings.logo'))
                                            <img id="church_logo_preview"
                                                src="{{ \Storage::url(config('settings.logo')) }}"
                                                style="width:360px;height:90px;"
                                                class="object-contain rounded border border-gray-200 bg-gray-50">
                                            @else
                                            <div id="church_logo_preview"
                                                style="width:360px;height:90px;"
                                                class="rounded border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center text-gray-400 text-xs">
                                                No logo uploaded
                                            </div>
                                            @endif
                                        </div>
                                        <input type="file" name="church_logo" id="church_logo"
                                            accept="image/png,image/jpeg,image/gif,image/svg+xml"
                                            class="tw-form-control w-full"
                                            onchange="previewLogo(this)">
                                        <p class="mt-1.5 text-xs text-gray-400 leading-relaxed">
                                            Preferred size: <strong>360 &times; 90 px</strong> (4&thinsp;:&thinsp;1 ratio) &mdash; PNG or SVG with transparent background. Max 2 MB.
                                        </p>
                                        <span class="text-red-500 text-xs block mt-1">{{ $errors->first('church_logo') }}</span>
                                    </td>
                                </tr>

                                {{-- Favicon --}}
                                <tr>
                                    <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                        <label class="text-sm font-medium text-gray-700 leading-8">Favicon</label>
                                    </th>
                                    <td class="py-4 px-6">
                                        {{-- 64×64 display preview (shown at a readable size) --}}
                                        <div class="mb-3 flex items-center gap-4">
                                            <div style="width:64px;height:64px;" class="flex-shrink-0">
                                                @if(\Config::get('settings.favicon'))
                                                <img id="favicon_preview"

                                                    src="{{ \Storage::url(config('settings.favicon')) }}"
                                                    style="width:64px;height:64px;"
                                                    class="object-contain rounded border border-gray-200 bg-gray-50 p-1">
                                                @else
                                                <div id="favicon_preview"
                                                    style="width:64px;height:64px;"
                                                    class="rounded border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center text-gray-400 text-xs text-center">
                                                    ICO
                                                </div>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-400 leading-relaxed">
                                                Shown in browser tab &amp; bookmarks.<br>
                                                <strong class="text-gray-600">512 &times; 512 px PNG only.</strong>
                                            </div>
                                        </div>
                                        <input type="file" name="favicon" id="favicon"
                                            accept="image/png"
                                            class="tw-form-control w-full"
                                            onchange="previewFavicon(this)">
                                        <span class="text-red-500 text-xs block mt-1">{{ $errors->first('favicon') }}</span>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
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

@push('scripts')
<script>
    function previewLogo(input) {
        var file = input.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function(e) {
            var el = document.getElementById('church_logo_preview');
            if (el.tagName === 'IMG') {
                el.src = e.target.result;
            } else {
                var img = document.createElement('img');
                img.id = 'church_logo_preview';
                img.src = e.target.result;
                img.style.cssText = 'width:360px;height:90px;';
                img.className = 'object-contain rounded border border-gray-200 bg-gray-50';
                el.parentNode.replaceChild(img, el);
            }
        };
        reader.readAsDataURL(file);
    }

    function previewFavicon(input) {
        var file = input.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function(e) {
            var el = document.getElementById('favicon_preview');
            if (el.tagName === 'IMG') {
                el.src = e.target.result;
            } else {
                var img = document.createElement('img');
                img.id = 'favicon_preview';
                img.src = e.target.result;
                img.style.cssText = 'width:64px;height:64px;';
                img.className = 'object-contain rounded border border-gray-200 bg-gray-50 p-1';
                el.parentNode.replaceChild(img, el);
            }
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush