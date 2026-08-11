<?php

namespace App\Http\Resources\API\Guest;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowPhotos extends JsonResource
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
            'id'            =>  $this->id,
            'church_id'     =>  $this->church_id,
            'gallery_id'    =>  $this->gallery_id,
            'name'          =>  $this->gallery->name,
            'path'          =>  $this->FullPath,
        ];
    }
}