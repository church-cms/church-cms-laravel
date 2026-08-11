<?php

namespace App\Http\Resources\API;

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
            'id'            =>  $this->id,
            'church_id'     =>  $this->church_id, 
            'name'          =>  $this->name,        
            'description'   =>  $this->description,
            'media_type'    =>  $this->media_type,
            //'type'          =>  $this->type,
            'url'           =>  $url,
        ];
    }
}