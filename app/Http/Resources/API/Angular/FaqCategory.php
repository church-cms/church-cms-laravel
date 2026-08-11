<?php

namespace App\Http\Resources\API\Angular;

use Illuminate\Http\Resources\Json\JsonResource;

class FaqCategory extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[
            //
            'id'        =>  $this->id,
            'church_id' =>  $this->church_id,
            'name'      =>  $this->name,
        ];
    }
}