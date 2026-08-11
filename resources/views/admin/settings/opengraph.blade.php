@extends('layouts.admin.layout')
@section('content')
<div class="w-full flex flex-col">
<h1 class="text-xl font-semibold mb-6 text-gray-700">Settings</h1>
<div class="w-full main-content bg-white flex h-auto">
    <div class="flex flex-col lg:flex-row w-full">
        @include('layouts.admin.settingsbar')
        <div class="flex-1 px-8 py-6 min-w-0">
            @include('partials._page_header', ['pageTitle' => 'Open Graph Meta Tags'])
            @include('partials.message')

            <form method="POST" action="{{ url('/admin/settings/opengraph') }}" enctype="multipart/form-data">
                @csrf

                {{-- Facebook Open Graph --}}
                <div class="bg-white border border-gray-200 rounded shadow-sm mb-6 max-w-3xl">
                    <div class="px-6 py-3 border-b border-gray-100 bg-gray-50">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Facebook Open Graph</p>
                    </div>
                    <table class="form-table w-full">
                        <tbody>
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                    <label for="facebook_title" class="text-sm font-medium text-gray-700 leading-8">Title</label>
                                </th>
                                <td class="py-4 px-6">
                                    <input type="text" name="facebook_title" id="facebook_title"
                                        class="tw-form-control w-full"
                                        placeholder="Page title shown when shared on Facebook"
                                        value="{{ old('facebook_title', \Config::get('settings.facebook_title')) }}">
                                    <span class="text-red-500 text-xs block mt-1">{{ $errors->first('facebook_title') }}</span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                    <label for="facebook_description" class="text-sm font-medium text-gray-700 leading-8">Description</label>
                                </th>
                                <td class="py-4 px-6">
                                    <textarea name="facebook_description" id="facebook_description" rows="3"
                                        class="tw-form-control w-full"
                                        placeholder="Short description for Facebook sharing">{{ old('facebook_description', \Config::get('settings.facebook_description')) }}</textarea>
                                    <span class="text-red-500 text-xs block mt-1">{{ $errors->first('facebook_description') }}</span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                    <label for="facebook_url" class="text-sm font-medium text-gray-700 leading-8">URL</label>
                                </th>
                                <td class="py-4 px-6">
                                    <input type="url" name="facebook_url" id="facebook_url"
                                        class="tw-form-control w-full"
                                        placeholder="https://yourchurch.com"
                                        value="{{ old('facebook_url', \Config::get('settings.facebook_url')) }}">
                                    <span class="text-red-500 text-xs block mt-1">{{ $errors->first('facebook_url') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                    <label for="facebook_image" class="text-sm font-medium text-gray-700 leading-8">Image</label>
                                </th>
                                <td class="py-4 px-6">
                                    @if($facebook_image_url)
                                        <img id="facebook_image_preview" src="{{ $facebook_image_url }}"
                                            class="mb-2 rounded border border-gray-200" style="max-width:200px; max-height:105px; object-fit:cover;">
                                    @else
                                        <img id="facebook_image_preview" src="" class="mb-2 rounded border border-gray-200 hidden" style="max-width:200px; max-height:105px; object-fit:cover;">
                                    @endif
                                    <input type="file" name="facebook_image" id="facebook_image"
                                        class="tw-form-control w-full" accept="image/*"
                                        onchange="previewOgImage(this, 'facebook_image_preview')">
                                    <p class="mt-1 text-xs text-gray-400">Recommended: 1200 × 630px. Leave blank to keep current image.</p>
                                    <span class="text-red-500 text-xs block mt-1">{{ $errors->first('facebook_image') }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Twitter Open Graph --}}
                <div class="bg-white border border-gray-200 rounded shadow-sm mb-6 max-w-3xl">
                    <div class="px-6 py-3 border-b border-gray-100 bg-gray-50">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Twitter / X Card</p>
                    </div>
                    <table class="form-table w-full">
                        <tbody>
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                    <label for="twitter_title" class="text-sm font-medium text-gray-700 leading-8">Title</label>
                                </th>
                                <td class="py-4 px-6">
                                    <input type="text" name="twitter_title" id="twitter_title"
                                        class="tw-form-control w-full"
                                        placeholder="Page title shown when shared on Twitter / X"
                                        value="{{ old('twitter_title', \Config::get('settings.twitter_title')) }}">
                                    <span class="text-red-500 text-xs block mt-1">{{ $errors->first('twitter_title') }}</span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                    <label for="twitter_description" class="text-sm font-medium text-gray-700 leading-8">Description</label>
                                </th>
                                <td class="py-4 px-6">
                                    <textarea name="twitter_description" id="twitter_description" rows="3"
                                        class="tw-form-control w-full"
                                        placeholder="Short description for Twitter sharing">{{ old('twitter_description', \Config::get('settings.twitter_description')) }}</textarea>
                                    <span class="text-red-500 text-xs block mt-1">{{ $errors->first('twitter_description') }}</span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                    <label for="twitter_url" class="text-sm font-medium text-gray-700 leading-8">URL</label>
                                </th>
                                <td class="py-4 px-6">
                                    <input type="url" name="twitter_url" id="twitter_url"
                                        class="tw-form-control w-full"
                                        placeholder="https://yourchurch.com"
                                        value="{{ old('twitter_url', \Config::get('settings.twitter_url')) }}">
                                    <span class="text-red-500 text-xs block mt-1">{{ $errors->first('twitter_url') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                    <label for="twitter_image" class="text-sm font-medium text-gray-700 leading-8">Image</label>
                                </th>
                                <td class="py-4 px-6">
                                    @if($twitter_image_url)
                                        <img id="twitter_image_preview" src="{{ $twitter_image_url }}"
                                            class="mb-2 rounded border border-gray-200" style="max-width:200px; max-height:105px; object-fit:cover;">
                                    @else
                                        <img id="twitter_image_preview" src="" class="mb-2 rounded border border-gray-200 hidden" style="max-width:200px; max-height:105px; object-fit:cover;">
                                    @endif
                                    <input type="file" name="twitter_image" id="twitter_image"
                                        class="tw-form-control w-full" accept="image/*"
                                        onchange="previewOgImage(this, 'twitter_image_preview')">
                                    <p class="mt-1 text-xs text-gray-400">Recommended: 1200 × 630px. Leave blank to keep current image.</p>
                                    <span class="text-red-500 text-xs block mt-1">{{ $errors->first('twitter_image') }}</span>
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
function previewOgImage(input, previewId) {
    var preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
