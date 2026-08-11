<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaFile extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->media_type == 'audio')
        {
            $url = $this->UrlPath;
        }
        else
        {
            if($this->type == 'url')
            {
                $url = $this->url;
            }
            else
            {
                $url = $this->UrlPath;
            }
        }
        return [
            //
            'id'            =>  $this->id,
            'name'          =>  $this->name,
            'description'   =>  \Str::limit($this->description,15,'...'),
            'media_type'    =>  $this->media_type,
            'type'          =>  $this->type,
            'url'           =>  $url,
        ];
    }
}