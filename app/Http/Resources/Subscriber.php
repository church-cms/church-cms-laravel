<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Subscriber extends JsonResource
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
            'id'           =>  $this->id,
            'firstname'    =>  $this->firstname,
            'lastname'     =>  $this->lastname,
            'email'        =>  $this->email,
            'aff'          =>  $this->aff,
            'source'       =>  $this->source,
            'is_active'    =>  $this->is_active,
          
           
          
        ];
    }
}
