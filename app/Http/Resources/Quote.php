<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Quote extends JsonResource
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
            //
            'id'            =>  $this->id,
            'image'         =>  $this->image == null ? null:$this->ImagePath,
            'text'          =>  $this->text,
            'tamil'         =>  $this->tamil_quotes,
            'english'       =>  $this->english_quotes,
            'publish_on'    =>  $this->publish_on == null ? null:date('d-m-Y H:i:s',strtotime($this->publish_on)),
        ];
    }
}