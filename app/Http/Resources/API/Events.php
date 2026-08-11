<?php

namespace App\Http\Resources\API;

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
        foreach ($this as $events) 
        {
            foreach ($events as $event) 
            {
                return [
                    'event_id'      =>  $event->id,
                    'church_id'     =>  $event->church_id,
                    'select_type'   =>  $event->select_type,
                    'title'         =>  $event->title,
                    'description'   =>  $event->description,
                    'repeats'       =>  $event->repeats,
                    'freq'          =>  $event->freq,
                    'freq_term'     =>  $event->freq_term,
                    'location'      =>  $event->location,
                    'category'      =>  $event->category,
                    'image'         =>  $event->ImagePath,
                    'start_date'    =>  date('d-m-Y H:i:s', strtotime($event->start_date)),
                    'end_date'      =>  date('d-m-Y H:i:s', strtotime($event->end_date)),
                ];
            }
        }
    }
}