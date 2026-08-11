<?php

namespace App\Http\Resources;

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
        if($this->type == 'month')
        {
            $year = date("F", mktime(0, 0, 0, $this->month, 1)).' '.$this->year;
        }
        else
        {
            $year = 'Week - '.$this->week.' '.$this->year;
        }

        return 
        [
            'id'            => $this->id,
            'name'          => $this->name,
            'type'          => $this->type,
            'date'          => $year,
            'cover_image'   => $this->CoverImagePath,
            'path'          => $this->FilePath,
        ];
    }
}