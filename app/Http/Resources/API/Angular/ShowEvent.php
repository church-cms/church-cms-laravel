<?php

namespace App\Http\Resources\API\Angular;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use DateTime;

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
        // $now = new DateTime($this->start_date);
        // $ago = new DateTime($this->end_date);
        // $diff = $now->diff($ago);
        // $diff->w = floor($diff->days / 7);

        // $diff->d -= $diff->w * 7;
        //                 dd($diff->w);

        $start_date = new DateTime($this->start_date);
        $end_date = new DateTime($this->end_date);
        $diff = $start_date->diff($end_date);
        $start_day = $start_date->format('l');
        $end_day = $end_date->format('l');
        return [
            'event_id'      =>  $this->id,
            'church_id'     =>  $this->church_id,
            'church_name' => $this->church->name,
            'select_type'   =>  $this->select_type,
            'title'         =>  $this->title,
            'description'   =>  $this->description,
            'repeats'       =>  $this->repeats,
            'freq'          =>  $this->freq,
            'freq_term'     =>  $this->freq_term,
            'location'      =>  $this->location,
            'category'      =>  $this->category,
            'image'         =>  $this->ImagePath,
          //  'start_date'    =>  date('d M Y', strtotime($this->start_date)),
            'start_date'    =>  date('d-M-Y H:i:s', strtotime($this->start_date)),
            'end_date'      =>  date('d-M-Y H:i:s', strtotime($this->end_date)),
            'days' => $diff->days,
            'hours' => $diff->h,
            'mins' => $diff->m,
            'secs' => $diff->s,
            'start_day' => $start_day,
            'end_day' => $end_day,
            'organised_by' => $this->organised_by,
            'created_at' => $this->created_at->format('d M Y l'),
            'date_format' => date('jS', strtotime($this->created_at)),
            'date_format1' => date('F, Y', strtotime($this->created_at)),
        ];
    }
}