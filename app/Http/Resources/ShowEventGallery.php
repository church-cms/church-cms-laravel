<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowEventGallery extends JsonResource
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
            'id'            =>  $this->id,
            'path'          =>  $this->FullPath,
            'updated_at'    =>  date('d-m-Y H:i:s', strtotime($this->updated_at)),
        ];
    }
}