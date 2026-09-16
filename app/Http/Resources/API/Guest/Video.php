<?php

namespace App\Http\Resources\API\Guest;

use Illuminate\Http\Resources\Json\JsonResource;

class Video extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->type == 'url')
        {
            $url = $this->url;
        }
        else
        {
            $url = $this->UrlPath;
        }
        
        return [
            //
            'id'            =>  $this->id,
            'name'          =>  $this->name,
            'description'   =>  \Str::limit($this->description,15,'...'),
            'type'          =>  $this->type,
            'url'           =>  $url,
        ];
    }
}