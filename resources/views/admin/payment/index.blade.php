@extends('layouts.admin.layout')

@section('content')

    <div class="main">
        <div class="mb-4">
            <img src="{{ url('/uploads/payumoney.png') }}">
        </div>
        <form action="#" id="payment_form">
            <input type="hidden" class="tw-form-control w-1/4" id="key" name="key" placeholder="Merchant Key"
                value="{{ $key }}">
            <input type="hidden" class="tw-form-control w-1/4" id="salt" name="salt" placeholder="Merchant Salt"
                value="{{ $salt }}">
            <input type="hidden" class="tw-form-control w-1/4" id="udf5" name="udf5" value="{{ $udf5 }}">
            <input type="hidden" class="tw-form-control w-1/4" id="surl" name="surl" value="{{ $surl }}">
            <input type="hidden" class="tw-form-control w-1/4" id="url" name="url" value="{{ $url }}">
            <input type="hidden" class="tw-form-control w-full" id="hash" name="hash" placeholder="Hash"
                value="{{ $hash }}">
            <input type="hidden" class="tw-form-control w-1/4" id="amount" name="amount" value="{{ $amount }}">
            <input type="hidden" class="tw-form-control w-1/4" id="udf1" name="udf1" value="{{ $udf1 }}">

            <div class="dv">
                <span class="text">
                    <label>Transaction ID:</label>
                </span>
                <span>
                    <input type="text" class="tw-form-control w-1/4" id="txnid" name="txnid" value="{{ $txnid }}"
                        readonly>
                </span>
            </div>

            <div class="dv">
                <span class="text">
                    <label>Product Info:</label>
                </span>
                <span>
                    <input type="text" class="tw-form-control w-1/4" id="pinfo" name="pinfo" value="{{ $pinfo }}"
                        readonly>
                </span>
            </div>

            <div class="dv">
                <span class="text">
                    <label>User Name:</label>
                </span>
                <span>
                    <input type="text" class="tw-form-control w-1/4" id="fname" name="fname" placeholder="User Name"
                        value="{{ $fname }}">
                </span>
            </div>

            <div class="dv">
                <span class="text">
                    <label>Email ID:</label>
                </span>
                <span>
                    <input type="text" class="tw-form-control w-1/4" id="email" name="email" placeholder="Email ID"
                        value="{{ $email }}">
                </span>
            </div>

            <div class="dv">
                <span class="text">
                    <label>Mobile/Cell Number:</label>
                </span>
                <span>
                    <input type="text" class="tw-form-control w-1/4" id="mobile" name="mobile"
                        placeholder="Mobile/Cell Number" value="{{ $mobile }}">
                </span>
            </div>

            <div>
                <input type="submit" class="btn btn-primary submit-btn cursor-pointer" value="Pay"
                    onclick="launchBOLT(); return false;">
            </div>
        </form>
    </div>

@endsection

@push('scripts')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

    <!-- this meta viewport is required for BOLT //-->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- BOLT Sandbox/test //-->
    <script id="bolt" src="https://sboxcheckout-static.citruspay.com/bolt/run/bolt.min.js" bolt-color="e34524"
        bolt-logo="http://boltiswatching.com/wp-content/uploads/2015/09/Bolt-Logo-e14421724859591.png"></script>

    <!-- BOLT Production/Live //-->
    <script id="bolt" src="https://checkout-static.citruspay.com/bolt/run/bolt.min.js" bolt-color="e34524"
               bolt-logo="http://boltiswatching.com/wp-content/uploads/2015/09/Bolt-Logo-e14421724859591.png"></script> //-->

    <script type="text/javascript">
        $('#payment_form').bind('keyup blur', function() {
            $.ajax({
                url: $('#url').val(),
                type: 'post',
                data: JSON.stringify({
                    key: $('#key').val(),
                    salt: $('#salt').val(),
                    txnid: $('#txnid').val(),
                    amount: $('#amount').val(),
                    pinfo: $('#pinfo').val(),
                    fname: $('#fname').val(),
                    email: $('#email').val(),
                    mobile: $('#mobile').val(),
                    udf5: $('#udf5').val(),
                    udf1: $('#udf1').val()
                }),
                contentType: "application/json",
                dataType: 'json',
                success: function(json) {
                    if (json['error']) {
                        $('#alertinfo').html('<i class="fa fa-info-circle"></i>' + json['error']);
                    } else if (json['success']) {
                        $('#hash').val(json['success']);
                    }
                }
            });
        });
    </script>

    <script type="text/javascript">
        function launchBOLT() {
            bolt.launch({
                key: $('#key').val(),
                txnid: $('#txnid').val(),
                hash: $('#hash').val(),
                amount: $('#amount').val(),
                firstname: $('#fname').val(),
                email: $('#email').val(),
                phone: $('#mobile').val(),
                productinfo: $('#pinfo').val(),
                udf5: $('#udf5').val(),
                udf1: $('#udf1').val(),
                surl: $('#surl').val(),
                furl: $('#surl').val(),
                mode: 'dropout'
            }, {
                responseHandler: function(BOLT) {
                    //console.log( BOLT.response.txnStatus );		
                    if (BOLT.response.txnStatus != 'CANCEL') {
                        //Salt is passd here for demo purpose only. For practical use keep salt at server side only.
                        var fr = '<form action=\"' + $('#surl').val() + '\" method=\"post\">' +
                            '<input type=\"hidden\" name=\"key\" value=\"' + BOLT.response.key + '\" />' +
                            '<input type=\"hidden\" name=\"salt\" value=\"' + $('#salt').val() + '\" />' +
                            '<input type=\"hidden\" name=\"txnid\" value=\"' + BOLT.response.txnid + '\" />' +
                            '<input type=\"hidden\" name=\"amount\" value=\"' + BOLT.response.amount + '\" />' +
                            '<input type=\"hidden\" name=\"productinfo\" value=\"' + BOLT.response.productinfo +
                            '\" />' +
                            '<input type=\"hidden\" name=\"firstname\" value=\"' + BOLT.response.firstname +
                            '\" />' +
                            '<input type=\"hidden\" name=\"email\" value=\"' + BOLT.response.email + '\" />' +
                            '<input type=\"hidden\" name=\"udf5\" value=\"' + BOLT.response.udf5 + '\" />' +
                            '<input type=\"hidden\" name=\"udf1\" value=\"' + BOLT.response.udf1 + '\" />' +
                            '<input type=\"hidden\" name=\"mihpayid\" value=\"' + BOLT.response.mihpayid +
                            '\" />' +
                            '<input type=\"hidden\" name=\"status\" value=\"' + BOLT.response.status + '\" />' +
                            '<input type=\"hidden\" name=\"hash\" value=\"' + BOLT.response.hash + '\" />' +
                            '</form>';
                        var form = jQuery(fr);
                        jQuery('body').append(form);
                        form.submit();
                    }
                },
                catchException: function(BOLT) {
                    alert(BOLT.message);
                }
            });
        }
    </script>

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
