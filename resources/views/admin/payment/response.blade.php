@extends('layouts.admin.layout')

@section('content')

    @php
    $postdata = $_REQUEST;
    $msg = '';
    if (isset($postdata['key'])) {
        $key = $postdata['key'];
        $salt = 'kbwgdh7TaL';
        $txnid = $postdata['txnid'];
        $amount = $postdata['amount'];
        $productInfo = $postdata['productinfo'];
        $firstname = $postdata['firstname'];
        $email = $postdata['email'];
        $udf5 = $postdata['udf5'];
        $udf1 = $postdata['udf1'];
        $mihpayid = $postdata['mihpayid'];
        $status = $postdata['status'];
        $resphash = $postdata['hash'];
        //Calculate response hash to verify
        $keyString = $key . '|' . $txnid . '|' . $amount . '|' . $productInfo . '|' . $firstname . '|' . $email . '|' . $udf1 . '||||' . $udf5 . '|||||';
        $keyArray = explode('|', $keyString);
        $reverseKeyArray = array_reverse($keyArray);
        $reverseKeyString = implode('|', $reverseKeyArray);
        $CalcHashString = strtolower(hash('sha512', $salt . '|' . $status . '|' . $reverseKeyString));

        if ($status == 'success' && $resphash == $CalcHashString) {
            $msg = 'Transaction Successful and Hash Verified...';
            //Do success order processing here...
        } else {
            //tampered or failed
            $msg = 'Payment failed for Hash not verified...';
        }
    } else {
        exit(0);
    }
    @endphp

    <div class="main">
        <div>
            <img src="{{ url('/uploads/payumoney.png') }}">
        </div>

        <div class="dv">
            <span class="text">
                <label>Transaction ID:</label>
            </span>
            <span>{{ $txnid }}</span>
        </div>

        <div class="dv">
            <span class="text">
                <label>Amount:</label>
            </span>
            <span>{{ $amount }}</span>
        </div>

        <div class="dv">
            <span class="text">
                <label>Info:</label>
            </span>
            <span>{{ $productInfo }}</span>
        </div>

        <div class="dv">
            <span class="text">
                <label>Plan ID :</label>
            </span>
            <span>{{ $udf1 }}</span>
        </div>

        <div class="dv">
            <span class="text">
                <label>First Name:</label>
            </span>
            <span>{{ $firstname }}</span>
        </div>

        <div class="dv">
            <span class="text">
                <label>Email ID:</label>
            </span>
            <span>{{ $email }}</span>
        </div>

        <div class="dv">
            <span class="text">
                <label>Transaction Status:</label>
            </span>
            <span>{{ $status }}</span>
        </div>

        <div class="dv">
            <span class="text">
                <label>Message:</label>
            </span>
            <span> {{ $msg }}</span>
        </div>
    </div>

@endsection

@push('scripts')

    <style type="text/css">
        .main {
            margin-left: 30px;
            font-family: Verdana, Geneva, sans-serif, serif;
        }

        .text {
            float: left;
            width: 180px;
        }

        .dv {
            margin-bottom: 5px;
        }

    </style>

@endpush
