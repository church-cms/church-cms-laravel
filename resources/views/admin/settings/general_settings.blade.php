@extends('layouts.app')
@section('content')
    <div class="container mx-auto">
        <div class="w-full my-3 lg:mx-8 md:mx-8 px-3 lg:px-0 md:px-0">
            <h1 class="my-3">General Settings</h1>

            <form method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div class="tw-form-group">
                    <label class="tw-form-label">Site Title</label>
                    <input type="text" name="sitetitle" value="{{ \Config::get('settings.sitetitle') }}"
                        class="tw-form-control w-full lg:w-128">
                    <span class="text-danger">{{ $errors->first('sitetitle') }}</span>
                </div>
                <div class="tw-form-group">
                    <label class="tw-form-label">Site Name</label>
                    <input type="text" name="sitename" value="{{ \Config::get('settings.sitename') }}"
                        class="tw-form-control w-full lg:w-128">
                    <span class="text-danger">{{ $errors->first('sitename') }}</span>
                </div>
                <div class="tw-form-group">
                    <label class="tw-form-label">Site Logo</label>
                    <input type="file" name="sitelogo" class="p-2 border border-dashed lg:w-128">
                    @if(\Config::get('settings.sitelogo'))
                    <img src="{{ url(\Config::get('settings.sitelogo')) }}" class="lg:w-64 h-auto my-2">
                    @endif
                    <span class="text-danger">{{ $errors->first('sitelogo') }}</span>
                </div>
                <div class="tw-form-group">
                    <label class="tw-form-label">Site Favicon</label>

                    <input type="file" name="favicon" class="p-2 border border-dashed lg:w-128">
                    @if(\Config::get('settings.favicon'))
                    <img src="{{ url(\Config::get('settings.favicon')) }}" class="lg:w-64 h-auto my-2">
                    @endif

                </div>
                <input type="submit" value="Submit" name="submit" class="btn btn-submit">


            </form>
        </div>
        <!-- settings form end -->
    </div>
@endsection
