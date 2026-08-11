<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowEvent extends JsonResource
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

            'id'                => $this->id,     
            'church_id'         => $this->church_id,
            'select_type'       => $this->select_type,
            'title'             => $this->title,
            'description'       => $this->description,
            'repeats'           => $this->repeats,
            'freq'              => $this->freq,
            'freq_term'         => $this->freq_term,
            'location'          => $this->location,
            'category'          => $this->category,
            'image'             => $this->ImagePath,
            'start_date'        => date('d-m-Y H:i:s', strtotime($this->start_date)),
            'end_date'          => date('d-m-Y H:i:s', strtotime($this->end_date)),
            'date'              => date('d', strtotime($this->start_date)),
            'month'             => date('M', strtotime($this->start_date)),
            'description_limit' => \Str::limit($this->description,30,'...')

        ];
    }
}