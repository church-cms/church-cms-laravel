<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Help extends JsonResource
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
            'id'                =>  $this->id,
            'user_name'         =>  $this->user->name,
            'user_fullname'     =>  $this->user->FullName,
            'title'             =>  $this->title,
            'description'       =>  \Str::limit($this->description,50,'...'),
            'contact_details'   =>  $this->contact_details,
            'status'            =>  $this->status,
        ];
    }
}