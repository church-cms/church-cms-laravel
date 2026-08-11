<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class EditEvent extends JsonResource
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
       
            'church_id' =>$this->church_id,
            'select_type' =>$this->select_type,
            'title' => $this->title,
            'description' => $this->description,
            'repeats'=>$this->repeats,
            'freq' => $this->freq,
            'freq_term' =>$this->freq_term,
            'location' =>$this->location,
            'category' =>$this->category,
            'image' =>$this->ImagePath,
            'start_date' =>date('d-m-Y', strtotime($this->start_date)),
            'end_date' => date('d-m-Y', strtotime($this->end_date)),

        ];
    }
}