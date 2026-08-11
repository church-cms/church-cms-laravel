<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'id'           => $this->id,
            'name'         => $this->gatewayname,
            'display_name' => $this->displayname,
            'instructions' => $this->instructions,
            'currency'     => $this->currency,
            'status'       => $this->status,
        ];
    }
}
