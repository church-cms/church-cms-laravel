<?php

namespace App\Http\Resources;

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
            'organised_by'=>$this->organised_by,
            'image'      => $this->ImagePath,
            'image_raw'  => $this->image,
            'start_date' => date('d-m-Y H:i:s', strtotime($this->start_date)),
            'end_date'   => date('d-m-Y H:i:s', strtotime($this->end_date)),

        ];
    }
}
