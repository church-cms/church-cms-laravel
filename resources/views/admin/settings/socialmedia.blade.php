@extends('layouts.admin.layout')
@section('content')
<div class="w-full flex flex-col">
<h1 class="text-xl font-semibold mb-6 text-gray-700">Settings</h1>
<div class="w-full main-content bg-white flex h-auto">
    <div class="flex flex-col lg:flex-row w-full">
        @include('layouts.admin.settingsbar')
        <div class="flex-1 px-8 py-6 min-w-0">
            @include('partials._page_header', ['pageTitle' => 'Social Media'])
            @include('partials.message')

            <form method="POST" action="{{ url('/admin/settings/socialmedia') }}">
                @csrf
                <div class="bg-white border border-gray-200 rounded shadow-sm mb-6 max-w-3xl">
                    <table class="form-table w-full">
                        <tbody>

                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                    <label for="facebook" class="text-sm font-medium text-gray-700 leading-8">Facebook</label>
                                </th>
                                <td class="py-4 px-6">
                                    <input type="url" name="facebook" id="facebook"
                                        class="tw-form-control w-full"
                                        placeholder="https://facebook.com/yourpage"
                                        value="{{ old('facebook', $churchdetail['facebook'] ?? '') }}">
                                    <span class="text-red-500 text-xs block mt-1">{{ $errors->first('facebook') }}</span>
                                </td>
                            </tr>

                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                    <label for="twitter" class="text-sm font-medium text-gray-700 leading-8">Twitter / X</label>
                                </th>
                                <td class="py-4 px-6">
                                    <input type="url" name="twitter" id="twitter"
                                        class="tw-form-control w-full"
                                        placeholder="https://twitter.com/yourhandle"
                                        value="{{ old('twitter', $churchdetail['twitter'] ?? '') }}">
                                    <span class="text-red-500 text-xs block mt-1">{{ $errors->first('twitter') }}</span>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                    <label for="instagram" class="text-sm font-medium text-gray-700 leading-8">Instagram</label>
                                </th>
                                <td class="py-4 px-6">
                                    <input type="url" name="instagram" id="instagram"
                                        class="tw-form-control w-full"
                                        placeholder="https://instagram.com/yourprofile"
                                        value="{{ old('instagram', $churchdetail['instagram'] ?? '') }}">
                                    <span class="text-red-500 text-xs block mt-1">{{ $errors->first('instagram') }}</span>
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
