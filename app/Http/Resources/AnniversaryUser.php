<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnniversaryUser extends JsonResource
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
            'id'                    =>  $this->user->id,
            'fullname'              =>  $this->user->FullName,
            'firstname'             =>  $this->firstname,
            'lastname'              =>  $this->lastname,
            'mobile_no'             =>  $this->user->mobile_no,
            'email'                 =>  $this->user->email,
            'avatar'                =>  $this->AvatarPath,
            'marriage_start_date'   =>  date('d-m-Y',strtotime($this->marriage_start_date)),
        ];
    }
}