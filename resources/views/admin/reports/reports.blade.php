@extends('layouts.admin.layout')

@section('content')

    <div class="">
        <h1 class="admin-h1 flex items-center"> Reports </h1>
    </div>

    <div class="flex flex-col lg:flex-row">
        <div class="flex flex-wrap w-full lg:w-1/2 lg:mr-3 my-2">
            <div class="bg-white shadow border border-grey px-5 py-3 w-full">
                <p>
                <h1 class="admin-h1 mb-5 flex items-center"> Membership </h1>
                </p>
                <ul class="leading-loose list-reset">
                    <li class="py-1">
                        <a href="{{ url('/admin/report/activeMembers') }}" class="flex items-center">
                            <img src="{{ url('uploads/icons/csv-download.svg') }}" class="w-5 h-5 mr-3"> Active Members
                        </a>
                    </li>
                    <li class="py-1">
                        <a href="{{ url('/admin/report/guestMembers') }}" class="flex items-center">
                            <img src="{{ url('uploads/icons/csv-download.svg') }}" class="w-5 h-5 mr-3"> Guest Members
                        </a>
                    </li>
                    <li class="py-1">
                        <a href="{{ url('/admin/report/suspendedMembers') }}" class="flex items-center">
                            <img src="{{ url('uploads/icons/csv-download.svg') }}" class="w-5 h-5 mr-3">Suspended /
                            Expired Members </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="flex flex-wrap w-full lg:w-1/2 lg:mr-3 my-2">
            <div class="bg-white shadow border border-grey px-5 py-3 w-full">
                <p>
                <h1 class="admin-h1 mb-5 flex items-center"> Message History </h1>
                </p>
                <ul class="leading-loose list-reset">
                    <li class="py-1">
                        <a href="{{ url('/admin/report/messageHistory/Event Remainder Mail') }}"
                            class="flex items-center">
                            <img src="{{ url('uploads/icons/csv-download.svg') }}" class="w-5 h-5 mr-3"> Events </a>
                    </li>
                    <li class="py-1">
                        <a href="{{ url('/admin/report/messageHistory/Prayer Request Remainder Mail') }}"
                            class="flex items-center">
                            <img src="{{ url('uploads/icons/csv-download.svg') }}" class="w-5 h-5 mr-3"> Prayer Requests
                        </a>
                    </li>
                    <li class="py-1">
                        <a href="{{ url('/admin/report/messageHistory/Birthday Wishes') }}" class="flex items-center">
                            <img src="{{ url('uploads/icons/csv-download.svg') }}" class="w-5 h-5 mr-3">Birthday </a>
                    </li>
                    <li class="py-1">
                        <a href="{{ url('/admin/report/messageHistory/Anniversary Wishes') }}" class="flex items-center">
                            <img src="{{ url('uploads/icons/csv-download.svg') }}" class="w-5 h-5 mr-3"> Anniversary </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row">
        <div class="flex flex-wrap w-full lg:w-1/2 lg:mr-3 my-2">
            <div class="bg-white shadow border border-grey px-5 py-3 w-full">
                <p>
                <h1 class="admin-h1 mb-5 flex items-center"> Important Dates </h1>
                </p>
                <ul class="leading-loose list-reset">
                    <li class="py-1">
                        <a href="{{ url('/admin/report/birthday') }}" id="birthday" class="flex items-center">
                            <img src="{{ url('uploads/icons/csv-download.svg') }}" class="w-5 h-5 mr-3"> Birthday </a>
                    </li>
                    <li class="py-1">
                        <a href="{{ url('/admin/report/anniversary') }}" class="flex items-center">
                            <img src="{{ url('uploads/icons/csv-download.svg') }}" class="w-5 h-5 mr-3"> Anniversary </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        if ("{{ Session::get('path') }}" != null) {

            $("#birthday").on('click', function() {
                var link = "{{ Session::get('path') }}";
                alert(link)
                window.open("{{ Session::get('path') }}", 'popUpWindow',
                    'height=500,width=500,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes'
                    );
                {{ session()->forget('path') }}
            });
        } else {
            {{ session()->forget('path') }}
        }
    </script> -->

@endpush
