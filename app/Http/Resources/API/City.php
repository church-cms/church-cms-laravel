<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class City extends JsonResource
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
           'id'         => $this->id,
           'country_id' => $this->country_id,
           'state_id'   => $this->state_id,
           'name'       => $this->name,
           'status'     => $this->status,
       ];
    }
}
