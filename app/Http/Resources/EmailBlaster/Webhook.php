<?php

namespace App\Http\Resources\EmailBlaster;

use Illuminate\Http\Resources\Json\JsonResource;

class Webhook extends JsonResource
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
            //
            'id'            =>  $this->id,
            'name'          =>  $this->name,
            'mailinglist'   =>  $this->mailinglist->name,
            'status'        =>  $this->status,
        ];
    }
}