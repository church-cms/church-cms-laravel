<?php

namespace App\Http\Resources\API\Guest;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowSermonLink extends JsonResource
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
            'sermons_id'    =>  $this->sermons_id,
            'title'         =>  $this->sermons->title,
            'type'          =>  $this->type,
            'location'      =>  $this->location,
            'url'           =>  $this->UrlPath, 
        ];
    }
}