<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->visibility == 'all_class')
        {
            $visibility = str_replace('_', ' ', ucwords($this->visibility));
        }
        elseif($this->visibility == 'select_class')
        {
            $visibility = $this->StandardLink->StandardSection;
        }
        
        return 
        [
            //
            'id'                =>  $this->id,
            'title'             =>  $this->title,
            'description'       =>  strip_tags($this->description),
            'post_created_at'   =>  date('d M Y',strtotime($this->post_created_at)),
            'attachments'       =>  $this->AttachmentPath,
            //'visibility'        =>  $visibility,
            'created_by'        =>  $this->createdBy->FullName,
        ];
    }
}