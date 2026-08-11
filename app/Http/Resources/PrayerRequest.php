<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PrayerRequest extends JsonResource
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
            'user_name'     =>  $this->user->name,
            'user_fullname' =>  $this->user->FullName,
            'title'         =>  $this->title,
            'description'   =>  \Str::limit($this->description,'50','...'),
            'date'          =>  date('d-m-Y H:i:s',strtotime($this->date)),
            'image'         =>  $this->image == null ? null:$this->ImagePath,
            'audio'         =>  $this->audio == null ? '':$this->AudioPath,
            'status'        =>  $this->status,
            'date_format' => date('jS', strtotime($this->date)),
            'date_format1' => date('F, Y', strtotime($this->date)),
        ];
    }
}