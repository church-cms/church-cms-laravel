<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowVideo extends JsonResource
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

            'church_id'     => $this->church_id, 
            'name'          => $this->name,        
            'description'   => $this->description,
            'type'          => $this->type,
            'url'           => $this->UrlPath,

            

        ];
    }
}