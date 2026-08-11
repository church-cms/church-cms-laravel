<?php

namespace App\Http\Resources\API\Guest;

use Illuminate\Http\Resources\Json\JsonResource;

class Events extends JsonResource
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
            'id'            =>  $this->id,
            'select_type'   =>  $this->select_type,
            'title'         =>  $this->title,
            'description'   =>  $this->description,
            'repeats'       =>  $this->repeats,
            'freq'          =>  $this->freq,
            'freq_term'     =>  $this->freq_term,
            'location'      =>  $this->location,
            'category'      =>  $this->category,
            'image'         =>  $this->ImagePath,
            'start_date'    =>  date('d M Y H:i:s', strtotime($this->start_date)),
            'end_date'      =>  date('d M Y H:i:s', strtotime($this->end_date)),
        ];
    }
}