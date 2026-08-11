<?php

namespace App\Http\Resources\API\Guest;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Payaccount;

class PaymentgatewayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
   
    public function toArray($request)
    {
      $church_id=$request->segment(4);
      $payaccount=Payaccount::where([['church_id',$church_id],['paymentgateway_id',$this->id],['status',1]])->first();
      if($payaccount)
      {
        $status=1;
      }
      else
      {
        $status=0;
      }

        return[
            'id'=>(int)$this->id,
            'name'=>$this->gatewayname,
            'display_name'=>$this->displayname,
            'instructions'=>$this->instructions,
            'status'=>$status,

        ];
    }
}
