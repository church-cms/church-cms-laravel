<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class Quotes extends JsonResource
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
            'id'                =>  $this->id,
            'image'             =>  $this->image == null ? null:$this->ImagePath,
            'text'              =>  $this->text,
            'tamil_quotes'      =>  $this->tamil_quotes,
            'english_quotes'    =>  $this->english_quotes,
            'publish_on'        =>  date('d-m-Y H:i:s',strtotime($this->publish_on)),
        ];
    }
}