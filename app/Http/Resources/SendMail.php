<?php

namespace App\Http\Resources;

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
            'mode'          =>  ucwords($this->mode),
            'subject'       =>  $this->subject,
            'message'       =>  \Str::limit($this->message,20,'...'),
            'attachments'   =>  $this->attachments == null ? null:$this->AttachmentPath,
            'date'          =>  date('d-m-Y H:i:s',strtotime($this->executed_at)),
            'status'        =>  ucwords($this->status),
        ];
    }
}