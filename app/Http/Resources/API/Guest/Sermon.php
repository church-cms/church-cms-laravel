<?php

namespace App\Http\Resources\API\Guest;

use Illuminate\Http\Resources\Json\JsonResource;

class Sermon extends JsonResource
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
            'date'          =>  date('d-m-Y H:i:s',strtotime($this->created_at)),
            'audio_count'   =>  $this->AudioCount,
            'video_count'   =>  $this->VideoCount,
            'file_count'    =>  $this->FileCount,
        ];
    }
}