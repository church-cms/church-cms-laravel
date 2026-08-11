<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class Country extends JsonResource
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
           'name'       => $this->name,
           'status'     => $this->status,
           'short_name' => $this->short_name,
          // 'iso_code'   => $this->iso_code,
          // 'tel_prefix' => $this->tel_prefix,
       ];
    }
}
