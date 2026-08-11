@extends('layouts.admin.layout')
@section('content')
<div class="w-full flex flex-col">
<h1 class="text-xl font-semibold mb-6 text-gray-700">Settings</h1>
<div class="w-full main-content bg-white flex h-auto">
    <div class="flex flex-col lg:flex-row w-full">
        @include('layouts.admin.settingsbar')
        <div class="flex-1 px-8 py-6 min-w-0">
            @include('partials._page_header', ['pageTitle' => 'SEO Settings'])
            @include('partials.message')

            {{-- Site Identity: Title & Name (moved from General) --}}
            <form method="POST" action="{{ url('/admin/settings/siteidentity') }}">
                @csrf
                <div class="bg-white border border-gray-200 rounded shadow-sm mb-6 max-w-3xl">
                    <div class="px-6 py-3 border-b border-gray-100 bg-gray-50">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Site Identity</p>
                    </div>
                    <table class="form-table w-full">
                        <tbody>
                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                    <label for="sitetitle" class="text-sm font-medium text-gray-700 leading-8">Site Title</label>
                                </th>
                                <td class="py-4 px-6">
                                    <input type="text" name="sitetitle" id="sitetitle"
                                        class="tw-form-control w-full"
                                        placeholder="Used in browser tab and emails"
                                        value="{{ old('sitetitle', \Config::get('settings.sitetitle')) }}">
                                    <span class="text-red-500 text-xs block mt-1">{{ $errors->first('sitetitle') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                    <label for="sitename" class="text-sm font-medium text-gray-700 leading-8">Site Name</label>
                                </th>
                                <td class="py-4 px-6">
                                    <input type="text" name="sitename" id="sitename"
                                        class="tw-form-control w-full"
                                        placeholder="Short display name for notifications"
                                        value="{{ old('sitename', \Config::get('settings.sitename')) }}">
                                    <span class="text-red-500 text-xs block mt-1">{{ $errors->first('sitename') }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mb-6">
                    <input type="submit" value="Save Changes" name="submit" class="btn btn-primary submit-btn cursor-pointer">
                </div>
            </form>

        </div>
    </div>
</div>
</div>
@endsection
