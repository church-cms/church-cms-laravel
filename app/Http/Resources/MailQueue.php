<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MailQueue extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return 
        [
            //
            'id'                =>  $this->id,
            'campaign'          =>  $this->campaign->name,
            'email'             =>  $this->email->subject,
            'subscriber_email'  =>  $this->subscriber->email,
            'mailinglist'       =>  $this->mailinglist->name,
            'scheduled_at'      =>  $this->scheduled_at == null ? null:date('d-m-Y h:i:s A',strtotime($this->scheduled_at)),
            'fired_at'          =>  $this->fired_at == null ? null:date('d-m-Y h:i:s A',strtotime($this->fired_at)),
            'is_read'           =>  $this->is_read,
            'clicks'            =>  $this->clicks,
        ];
    }
}