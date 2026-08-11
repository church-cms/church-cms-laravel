<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Userprofile;

class Birthday extends JsonResource
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
            'age'           =>   $this->present()->getAge($this['date_of_birth']),
            'date_of_birth' =>   date('d-m-Y',strtotime($this['date_of_birth'])),
            'id'            =>   $this->user->id,
            'name'          =>   $this->user->name,
            'avatar'        =>   $this->AvatarPath,
            'firstname'     =>   $this->firstname,
            'lastname'      =>   $this->lastname,
            'mobile_no'     =>   $this->user->mobile_no,
            'email'         =>   $this->user->email,
            'fullname'      =>   $this->user->FullName,
        ];
    }
}