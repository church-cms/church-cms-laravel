<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Email extends JsonResource
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
            'id'            =>  $this->id,
            'subject'       =>  $this->subject,
            'from_email'    =>  $this->from_email,
            'from_name'     =>  $this->from_name,
            'reply_to_email'=>  $this->reply_to_email,
            'content'       =>  $this->content,
        ];
    }
}