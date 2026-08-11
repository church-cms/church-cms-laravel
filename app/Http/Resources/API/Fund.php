<?php

namespace App\Http\Resources\API;

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
            //
            'id'        =>  $this->id,
            'church_id' =>  $this->church_id,
            'name'      =>  $name,
            //'method'    =>  ucwords($this->method),
            'method'    =>  ucwords($this->payaccount->paymentgateway->displayname),
            'amount'    =>  $this->amount,
            'status'    =>  ucwords($this->status),
            'created_at'=>  date('d-m-Y h:i A',strtotime($this->created_at))
        ];
    }
}