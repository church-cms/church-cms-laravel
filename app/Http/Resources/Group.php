<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Group extends JsonResource
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
        'group_id'    => $this->group->id,
        'name'        => $this->group->name,
        'cover_image' => $this->group->CoverImagePath,
        'started'     => date('M-Y',strtotime($this->group->created_at)),    
      ];
    }
}