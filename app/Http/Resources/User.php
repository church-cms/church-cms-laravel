<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{

   /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function toArray($request)
    {
        $profile   = $this->userprofile;
        $usergroup = $this->usergroup;

        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'email'           => $this->email,
            'mobile_no'       => $this->mobile_no,
            'profession'      => ucwords(str_replace('_', ' ', optional($profile)->profession ?? '')),
            'sub_occupation'  => optional($profile)->sub_occupation,
            'avatar'          => optional($profile)->AvatarPath,
            'firstname'       => optional($profile)->firstname,
            'lastname'        => optional($profile)->lastname,
            'fullname'        => trim(optional($profile)->firstname . ' ' . optional($profile)->lastname),
            'relation'        => optional($profile)->relation,
            'date_of_birth'   => optional($profile)->date_of_birth
                                    ? date('d M Y', strtotime($profile->date_of_birth))
                                    : null,
            'marriage_status' => optional($profile)->marriage_status,
            'state'           => ucwords(optional(optional($profile)->state)->name ?? ''),
            'city'            => ucwords(optional(optional($profile)->city)->name ?? ''),
            'usergroup'       => ucwords(optional($usergroup)->name ?? ''),
        ];
    }
}