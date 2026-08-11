<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowGallery extends JsonResource
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
            'id'            =>  $this->id,
            'church_id'     =>  $this->church_id,
            'name'          =>  $this->name,
            //'gallery_id'  =>  $this->gallery_id,
            'path'          =>  $this->FullPath,
            'no.of.photos'  =>  count($this->photos),
            //'updated_at'  =>  date('d-m-Y H:i:s', strtotime($this->updated_at)),
            'photos' => $this->photos,
        ];
    }
}