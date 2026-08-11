<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Fund extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->membership == 'member')
        {
            $name = $this->user->FullName;
        }
        else
        {
            $name = $this->data['first_name'].' '.$this->data['last_name'];
        }
        return [
            'id'        =>  $this->id,
            'name'      =>  $name,
            'payaccount'=>  $this->payaccount->paymentgateway->displayname,
            'amount'    =>  $this->amount,
            'method'    =>  ucwords(str_replace('_', ' ', $this->method)),
            'status'    =>  ucwords(str_replace('_', ' ', $this->status)),
        ];
    }
}