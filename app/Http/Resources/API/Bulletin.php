<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class Bulletin extends JsonResource
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
        'id'=>$this->id,
        'church_id'=>$this->church_id,
        'name' => $this->name,
        'type' => $this->type,
        'week' => $this->week,
        'month' => $this->month,
        'year' => $this->year,
        'cover_image' =>$this->CoverImagePath,
        'path' => $this->FilePath,
      ];
   }
}