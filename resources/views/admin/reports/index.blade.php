@extends('layouts.admin.layout')

@section('content')

    <div class="relative">
        <div class="flex flex-row justify-between">
            <form method="post" action="{{ url('/admin/report/filter') }}" enctype="multipart/form-data">
                @csrf
                <div>
                    <span>
                        <label>From: </label>
                    </span>
                    <span>
                        <input type="date" name="from_date" class="tw-form-control">
                        <span class="text-danger">{{ $errors->first('from_date') }}</span>
                    </span>
                    <span>
                        <label>To: </label>
                    </span>
                    <span>
                        <input type="date" name="to_date" class="tw-form-control">
                        <span class="text-danger">{{ $errors->first('to_date') }}</span>
                    </span>
                </div>
                <div>
                    <input type="Submit" name="Submit" value="Submit">
                </div>
            </form>
        </div>

        <div class="flex flex-row justify-between">
            <table class="w-full">
                <caption>
                    <h1 class="admin-h1 mb-6"> History</h1>
                </caption>
                <thead class="bg-grey-light">
                    <tr class="border-t-2 border-b-2">
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Plan</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Amount</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Status</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Subscription Start Date</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Subscription End Date</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-grey-light">
                   
                </tbody>
            </table>
        </div>
    </div>

@endsection
