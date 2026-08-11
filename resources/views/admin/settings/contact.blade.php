@extends('layouts.admin.layout')
@section('content')
<div class="w-full flex flex-col">
<h1 class="text-xl font-semibold mb-6 text-gray-700">Settings</h1>
<div class="w-full main-content bg-white flex h-auto">
    <div class="flex flex-col lg:flex-row w-full">
        @include('layouts.admin.settingsbar')
        <div class="flex-1 px-8 py-6 min-w-0">
            @include('partials._page_header', ['pageTitle' => 'Contact'])
            @include('partials.message')

            <form method="POST" action="{{ url('/admin/settings/contact') }}">
                @csrf
                <div class="bg-white border border-gray-200 rounded shadow-sm mb-6 max-w-3xl">
                    <table class="form-table w-full">
                        <tbody>

                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                    <label for="phone" class="text-sm font-medium text-gray-700 leading-8">Phone</label>
                                </th>
                                <td class="py-4 px-6">
                                    <input type="text" name="phone" id="phone"
                                        class="tw-form-control w-full"
                                        placeholder="+1 555 000 0000"
                                        value="{{ old('phone', $churchdetail['phone'] ?? '') }}">
                                    <span class="text-red-500 text-xs block mt-1">{{ $errors->first('phone') }}</span>
                                </td>
                            </tr>

                            <tr class="border-b border-gray-100">
                                <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                    <label for="email" class="text-sm font-medium text-gray-700 leading-8">Email</label>
                                </th>
                                <td class="py-4 px-6">
                                    <input type="email" name="email" id="email"
                                        class="tw-form-control w-full"
                                        placeholder="info@yourchurch.com"
                                        value="{{ old('email', $churchdetail['email'] ?? '') }}">
                                    <span class="text-red-500 text-xs block mt-1">{{ $errors->first('email') }}</span>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row" class="w-32 py-4 px-5 text-left align-top">
                                    <label for="website" class="text-sm font-medium text-gray-700 leading-8">Website</label>
                                </th>
                                <td class="py-4 px-6">
                                    <input type="url" name="website" id="website"
                                        class="tw-form-control w-full"
                                        placeholder="https://yourchurch.com"
                                        value="{{ old('website', $churchdetail['website'] ?? '') }}">
                                    <span class="text-red-500 text-xs block mt-1">{{ $errors->first('website') }}</span>
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
