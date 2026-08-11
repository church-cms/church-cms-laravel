<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Resources\Json\JsonResource;

class PayaccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                =>$this->id,
            'paymentgateway_id' =>$this->paymentgateway_id,
            'gatewayname'       =>$this->paymentgateway->gatewayname,
            'display_name'      =>$this->paymentgateway->displayname,
            'status'            =>$this->status,
            'comments'          =>$this->comments,
            'param1'            =>$this->param1 ?? '',
            'param2'            =>$this->param2 ?? '',
            'param3'            =>$this->param3 ?? '',
            'param4'            =>$this->param4 ?? '',
            'param5'            =>$this->param5 ?? '',
            'param6'            =>$this->param6 ?? '',
            'param7'            =>$this->param7 ?? '',
            'param8'            =>$this->param8 ?? '',
        ];
    }
}
