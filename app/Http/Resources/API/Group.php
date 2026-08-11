<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class Group extends JsonResource
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
            'cover_image'   =>  $this->CoverImagePath,
            'description'   =>  \Str::limit($this->description,50,'...'),
        ];
    }
}