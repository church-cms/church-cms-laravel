@extends('layouts.admin.layout')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ url('/admin/funds') }}" title="Back" id="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Offering Details</span>
        </h1>
        @if ($fund->status != 'deposited')
            <a href="{{ url('admin/funds/edit/' . $fund->id) }}" id="edit">
                <button type="submit"
                    class="no-underline text-white  px-4 mx-1 flex items-center blue-bg py-1 justify-center text-xs rounded">Edit</button>
            </a>
        @endif
    </div>
    <div class="bg-white shadow p-2 my-3">
        <div class="w-full lg:mr-8 md:mr-8 custom-table py-2">
            <table class="w-full">
                <tr class="border-t-2 border-b-2">
                    <td class="py-3 px-2">Name</td>
                    @if ($fund->membership == 'member')
                        <td class="py-3 px-2">
                            <a href="{{ url('/admin/member/show/' . $fund->user->name) }}"
                                class="underline-text">{{ $fund->user->FullName }}</a>
                        </td>
                    @else
                        <td class="py-3 px-2">{{ $fund->data['first_name'] }} {{ $fund->data['last_name'] }}</td>
                    @endif
                </tr>

                <tr class="border-t-2 border-b-2">
                    <td class="py-3 px-2">Authorized_at</td>
                    <td class="py-3 px-2">{{ date('d-m-Y H:i:s', strtotime($fund->authorised_at)) }} </td>
                </tr>

                <tr class="border-t-2 border-b-2">
                    <td class="py-3 px-2">Membership</td>
                    <td class="py-3 px-2">{{ ucwords($fund->membership) }} </td>
                </tr>

                @if ($fund->membership == 'guest')
                    <td class="py-3 px-2">Details</td>
                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2">Address</td>
                        <td class="py-3 px-2">{{ $fund->data['address'] }} </td>
                    </tr>

                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2">Mobile Number</td>
                        <td class="py-3 px-2">{{ $fund->data['mobile_number'] }} </td>
                    </tr>
                @endif

                <tr class="border-t-2 border-b-2">
                    <td class="py-3 px-2">Amount</td>
                    <td class="py-3 px-2">{{ $fund->amount }}</a></td>
                </tr>

                <tr class="border-t-2 border-b-2">
                    <td class="py-3 px-2">Payment Gateway</td>
                    <td class="py-3 px-2">{{ ucwords($fund->payaccount->paymentgateway->displayname) }}</td>
                </tr>

                @if ($fund->amount >= '100000')
                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2">Pan Number</td>
                        <td class="py-3 px-2">{{ $fund->data['pan_number'] }} </td>
                    </tr>
                @endif

                <tr class="border-t-2 border-b-2">
                    <td class="py-3 px-2">Method</td>
                    <td class="py-3 px-2">{{ ucwords($fund->method) }}</td>
                </tr>

                @if ($fund->method == 'cheque')
                    <td class="py-3 px-2">Payment Details</td>
                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2">Cheque Number</td>
                        <td class="py-3 px-2">{{ $fund->payment_details['cheque_number'] }}</td>
                    </tr>

                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2">Account Number</td>
                        <td class="py-3 px-2">{{ $fund->payment_details['account_number'] }}</td>
                    </tr>

                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2">Payee Name</td>
                        <td class="py-3 px-2">{{ $fund->payment_details['payee_name'] }}</td>
                    </tr>
                @elseif($fund->method == 'card')
                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2">Card Name</td>
                        <td class="py-3 px-2">{{ $fund->payment_details['card_name'] }}</td>
                    </tr>

                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2">Bank Name</td>
                        <td class="py-3 px-2">{{ $fund->payment_details['bank_name'] }}</td>
                    </tr>
                @elseif($fund->method == 'demanddraft')
                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2">Payable At</td>
                        <td class="py-3 px-2">{{ $fund->payment_details['payable_at'] }}</td>
                    </tr>

                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2">Account Number</td>
                        <td class="py-3 px-2">{{ $fund->payment_details['account_number'] }}</td>
                    </tr>
                @endif
                <tr class="border-t-2 border-b-2">
                    <td class="py-3 px-2">Status</td>
                    <td class="py-3 px-2">{{ ucwords($fund->status) }}</td>
                </tr>

                @if ($fund->status == 'cancel')
                    <tr class="border-t-2 border-b-2">
                        <td class="py-3 px-2">Comments</td>
                        <td class="py-3 px-2">{{ $fund->comments }}</td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
@endsection
