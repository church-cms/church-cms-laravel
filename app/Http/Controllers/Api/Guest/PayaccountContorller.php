<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Resources\API\Guest\PaymentgatewayResource;
use App\Http\Resources\Payment\PayaccountResource;
use App\Http\Controllers\Controller;
use App\Models\Paymentgateway;
use App\Models\Payaccount;


class PayaccountContorller extends Controller
{

    public function getlist($church_id)
    {

        $paymentgateways = Paymentgateway::get();
        $paymentgateways = PaymentgatewayResource::collection($paymentgateways);
        return $paymentgateways;
    }

    public function showdetails($church_id, $gateway_id)
    {
        $payaccount = Payaccount::where([['church_id', $church_id], ['paymentgateway_id', $gateway_id], ['status', 1]])->first();
        if ($payaccount)
            return response()->json([
                'success'   =>  true,
                'message'   =>  'Show Payaccount Details',
                'data'      =>  new PayaccountResource($payaccount)
            ], 200);
        else
            return response()->json([
                'success'   =>  false,
                'message'   =>  'Show Payaccount Details',
                'data'      =>  []
            ], 200);
    }
}
