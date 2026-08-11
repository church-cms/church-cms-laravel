<?php

namespace App\Http\Resources\API;

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
            'sermon_id'         =>  $this->id, 
            'author'            =>  $this->user->name,        
            'author_fullname'   =>  $this->user->FullName,        
            'title'             =>  $this->title,
            'description'       =>  $this->description,
            'cover_image'       =>  $this->CoverImagePath,
            'total_likes'       =>  $this->sermonlikevote,
            'total_unlikes'     =>  $this->sermonunlikevote,           
            'like'              =>  $this->likevote,
            'unlike'            =>  $this->unlikevote,
            'date'              =>  date('d-m-Y H:i:s',strtotime($this->created_at)),
        ];
    }
}