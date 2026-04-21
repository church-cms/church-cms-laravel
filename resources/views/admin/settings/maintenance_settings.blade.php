@extends('layouts.app')
@section('content')
<div class="container mx-auto">
    <div class="w-full my-3 lg:mx-8 md:mx-8 px-3 lg:px-0 md:px-0">

        <h1 class="my-3">Maintenance Settings</h1>

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
                    <tr>
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

                </tbody>
            </table>

            <div class="tw-form-row mt-4 mb-16">
                <input type="submit" value="Save Changes" name="submit" class="btn btn-primary submit-btn cursor-pointer">
            </div>

        </form>
    </div>
</div>
@endsection