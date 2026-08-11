<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Smtps extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return 
        [
            //
            'id'         =>  $this->id,
            'host'       =>  $this->host,
            'port'       =>  $this->port,
            'username'   =>  $this->username,
            'password'   =>  $this->password,
            'encryption' =>  strtoupper($this->encryption),
            'status'     =>  $this->status,
        ];
    }
}