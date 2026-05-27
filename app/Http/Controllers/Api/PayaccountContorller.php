<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\API\PaymentgatewayResource;
use App\Http\Resources\Payment\PayaccountResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Paymentgateway;
use App\Models\Payaccount;
use Exception;
use Log;
use OpenApi\Attributes as OA;

/**
 * PayaccountContorller
 *
 * Manages payment account information and payment methods via API.
 * Returns available payment gateways and payment account details.
 *
 * @package App\Http\Controllers\Api
 */
class PayaccountContorller extends Controller
{

    #[OA\Get(
        path: '/api/v1/paymentgateway',
        summary: 'List all available payment gateways',
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/PaymentgatewayResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function getlist()
    {

        $church_id=Auth::user()->church_id;

         $payaccounts=Payaccount::where([['church_id',$church_id],['status',1]])->distinct()->pluck('paymentgateway_id');
         $paymentgateways=Paymentgateway::get();
         $paymentgateways=PaymentgatewayResource::collection($paymentgateways);
          return $paymentgateways;

    }

    #[OA\Get(
        path: '/api/v1/payaccount/{gateway_id}',
        summary: 'Get payment account details for a gateway',
        parameters: [
            new OA\Parameter(
                name: 'gateway_id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/PayaccountDetailResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function showdetails($gateway_id)
    {
        $church_id=Auth::user()->church_id;
        $payaccount=Payaccount::where([['church_id',$church_id],['paymentgateway_id',$gateway_id],['status',1]])->first();
        if($payaccount)
        return response()->json([
                'success'   =>  true,
                'message'   =>  'Show Payaccount Details',
                'data'      =>  new PayaccountResource($payaccount)
            ],200);
        else
         return response()->json([
            'success'   =>  false,
            'message'   =>  'Show Payaccount Details',
            'data'      =>  []
        ],200);
    }

}
