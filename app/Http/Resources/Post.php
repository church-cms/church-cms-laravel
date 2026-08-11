<?php

namespace App\Http\Resources;

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
            'description'       =>  \Str::limit($this->description,50,'...'),
            'post_created_at'   =>  $this->post_created_at ? \Carbon\Carbon::parse($this->post_created_at)->diffForHumans() : null,
            'attachments'       =>  $this->AttachmentPath,
            //'visibility'        =>  $visibility,
            'created_by'        =>  $this->created_by,
        ];
    }
}
