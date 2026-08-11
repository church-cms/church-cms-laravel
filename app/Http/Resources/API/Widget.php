<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class Widget extends JsonResource
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
            'widget_id'         =>  $this->id, 
            'slug'              =>  $this->slug,        
            'content'           =>  $this->content,
            'date'              =>  date('d-m-Y H:i:s',strtotime($this->created_at)),
        ];
    }
}