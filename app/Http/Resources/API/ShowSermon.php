<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowSermon extends JsonResource
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
            'sermon_id'     =>  $this->id, 
            'author'        =>  $this->user->FullName,        
            'title'         =>  $this->title,
            'description'   =>  $this->description,
            'cover_image'   =>  $this->CoverImagePath,
            'total_likes'   =>  $this->sermonlikevote,
            'total_unlikes' =>  $this->sermonunlikevote,           
            'like'          =>  $this->likevote,
            'unlike'        =>  $this->unlikevote,
            'audio_count'   =>  $this->AudioCount,
            'video_count'   =>  $this->VideoCount,
            'file_count'    =>  $this->FileCount,
        ];
    }
}