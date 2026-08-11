<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class HelpUser extends JsonResource
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
           'id'                 => $this->id,
           'title'              => $this->title,
           'description'        => $this->description,
           'contact_details'    => $this->contact_details,
           'status'             => $this->status,
           'display_status'     => ucfirst($this->status),
           'expires_at'         => $this->expired_at==null ?'':date('d-m-Y h:i A', strtotime($this->expired_at)),
       ];
    }
}
