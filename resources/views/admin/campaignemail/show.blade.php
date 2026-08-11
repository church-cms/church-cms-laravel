@extends('layouts.admin.layout')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ $prev_url }}" title="Back" id="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Campaign Email Details</span>
        </h1>
    </div>
    <div class="bg-white shadow my-5">
        <div class="px-4 py-4">
            <div class="w-full lg:mr-8 md:mr-8 custom-table">
                <!--  py-4 -->
                <table class="w-full">
                    <tr class="border-t border-">
                        <td class="py-3 px-2 font-semibold">Email</td>
                        <td class="py-3 px-2">{{ $campaignemail->email->subject }}</td>
                    </tr>

                    <tr class="border-t border-b">
                        <td class="py-3 px-2 font-semibold">Delay In Days</td>
                        <td class="py-3 px-2">{{ $campaignemail->delay_in_days }}</td>
                    </tr>

                    <tr class="border-t border-b">
                        <td class="py-3 px-2 font-semibold">Delay In Hours</td>
                        <td class="py-3 px-2">{{ $campaignemail->delay_in_hours }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
