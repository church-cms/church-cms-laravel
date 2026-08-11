<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class SendMail extends JsonResource
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
            'subject'       =>  $this->subject==null ? '':$this->subject,
            'message'       =>  $this->message,
            'attachment'    =>  $this->attachments == null ? '':$this->AttachmentPath,    
            'sentAt'        =>  $this->fired_at == null ? '':$this->fired_at->diffForHumans(),
            'readStatus'    =>  $this->read_status,
            'readAt'        =>  $this->read_at == null ? '':$this->read_at->diffForHumans(),
        ];
    }
}