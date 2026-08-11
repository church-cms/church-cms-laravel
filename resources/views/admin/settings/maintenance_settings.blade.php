@extends('layouts.app')
@section('content')
<div class="container mx-auto">
    <div class="w-full my-3 lg:mx-8 md:mx-8 px-3 lg:px-0 md:px-0">

        @include('partials._page_header', ['pageTitle' => 'Maintenance Settings'])

           @include('partials.message')

        <form method="POST" action="" enctype="multipart/form-data">
            @csrf

            {{-- WordPress-style settings table --}}
            <table class="form-table w-full bg-white border border-gray-200 rounded shadow-sm mb-6">
                <tbody>

                    {{-- Maintenance Mode --}}
                    <tr class="border-b border-gray-100">
                        <th class="w-1/4 px-6 py-5 text-left align-top">
                            <label for="maintenance" class="font-semibold text-sm text-gray-700">
                                Maintenance Mode
                            </label>
                            <p class="text-xs text-gray-400 mt-1 font-normal">
                                Enable to show a maintenance page to visitors while you make changes.
                            </p>
                        </th>
                        <td class="px-6 py-5 align-middle">
                            <label class='toggle-label'>
                                <input type='checkbox' id="maintenance" name="maintenance" value="1"
                                    @if (Config::get('settings.maintenance')==1) checked @endif />
                                <span class='back'>
                                    <span class='toggle'></span>
                                    <span class='label on'>ON</span>
                                    <span class='label off'>OFF</span>
                                </span>
                            </label>
                        </td>
                    </tr>

                    {{-- User Registration Mode --}}
                    <tr class="border-b border-gray-100">
                        <th class="w-1/4 px-6 py-5 text-left align-top">
                            <label for="register" class="font-semibold text-sm text-gray-700">
                                User Registration
                            </label>
                            <p class="text-xs text-gray-400 mt-1 font-normal">
                                Allow new users to register an account on this site.
                            </p>
                        </th>
                        <td class="px-6 py-5 align-middle">
                            <label class='toggle-label'>
                                <input type='checkbox' id="register" name="register" value="1"
                                    @if (Config::get('settings.register_status')==1) checked @endif />
                                <span class='back'>
                                    <span class='toggle'></span>
                                    <span class='label on'>ON</span>
                                    <span class='label off'>OFF</span>
                                </span>
                            </label>
                        </td>
                    </tr>

                    {{-- User Login Mode --}}
                    <tr class="border-b border-gray-100">
                        <th class="w-1/4 px-6 py-5 text-left align-top">
                            <label for="login_status" class="font-semibold text-sm text-gray-700">
                                User Login
                            </label>
                            <p class="text-xs text-gray-400 mt-1 font-normal">
                                Allow existing users to log in to the site.
                            </p>
                        </th>
                        <td class="px-6 py-5 align-middle">
                            <label class='toggle-label'>
                                <input type='checkbox' id="login_status" name="login_status" value="1"
                                    @if (Config::get('settings.login_status')==1) checked @endif />
                                <span class='back'>
                                    <span class='toggle'></span>
                                    <span class='label on'>ON</span>
                                    <span class='label off'>OFF</span>
                                </span>
                            </label>
                        </td>
                    </tr>

                    {{-- Section divider --}}
                    <tr class="bg-gray-50">
                        <td colspan="2" class="px-6 py-3">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Public Website Access</p>
                        </td>
                    </tr>

                    {{-- Member Web Login --}}
                    <tr class="border-b border-gray-100">
                        <th class="w-1/4 px-6 py-5 text-left align-top">
                            <label for="member_web_login" class="font-semibold text-sm text-gray-700">
                                Member Web Login
                            </label>
                            <p class="text-xs text-gray-400 mt-1 font-normal">
                                Allow church members to log in via the website. Disable to direct members to the mobile app only.
                            </p>
                        </th>
                        <td class="px-6 py-5 align-middle">
                            <label class='toggle-label'>
                                <input type='checkbox' id="member_web_login" name="member_web_login" value="1"
                                    @if (Config::get('settings.member_web_login', 1)==1) checked @endif />
                                <span class='back'>
                                    <span class='toggle'></span>
                                    <span class='label on'>ON</span>
                                    <span class='label off'>OFF</span>
                                </span>
                            </label>
                        </td>
                    </tr>

                    {{-- Guest Login --}}
                    <tr class="border-b border-gray-100">
                        <th class="w-1/4 px-6 py-5 text-left align-top">
                            <label for="guest_login" class="font-semibold text-sm text-gray-700">
                                Guest Login
                            </label>
                            <p class="text-xs text-gray-400 mt-1 font-normal">
                                Allow registered guests to log in via the public website to submit prayer requests, help requests, and comments.
                            </p>
                        </th>
                        <td class="px-6 py-5 align-middle">
                            <label class='toggle-label'>
                                <input type='checkbox' id="guest_login" name="guest_login" value="1"
                                    @if (Config::get('settings.guest_login', 1)==1) checked @endif />
                                <span class='back'>
                                    <span class='toggle'></span>
                                    <span class='label on'>ON</span>
                                    <span class='label off'>OFF</span>
                                </span>
                            </label>
                        </td>
                    </tr>

                    {{-- Guest Registration --}}
                    <tr>
                        <th class="w-1/4 px-6 py-5 text-left align-top">
                            <label for="guest_registration" class="font-semibold text-sm text-gray-700">
                                Guest Registration
                            </label>
                            <p class="text-xs text-gray-400 mt-1 font-normal">
                                Allow new visitors to self-register as guests on the public website.
                            </p>
                        </th>
                        <td class="px-6 py-5 align-middle">
                            <label class='toggle-label'>
                                <input type='checkbox' id="guest_registration" name="guest_registration" value="1"
                                    @if (Config::get('settings.guest_registration', 1)==1) checked @endif />
                                <span class='back'>
                                    <span class='toggle'></span>
                                    <span class='label on'>ON</span>
                                    <span class='label off'>OFF</span>
                                </span>
                            </label>
                        </td>
                    </tr>

                </tbody>
            </table>

            <div class="tw-form-row mt-4 mb-16">
                <input type="submit" value="Save Changes" name="submit" class="btn btn-primary submit-btn cursor-pointer">
            </div>

        </form>
    </div>
</div>
@endsection