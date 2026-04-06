<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\Common;

class UserDetail extends JsonResource
{
    use Common;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->email_verified == '0')
        {
            $email_verified = "unverified";
        }
        else
        {
            $email_verified = "verified";
        }

        if(optional($this->userprofile)->avatar=='')
        {
            $avatar = $this->getFilePath('uploads/admin/member/avatar/images.jpg');
        }
        else
        {
            $avatar = $this->userprofile->AvatarPath;
        }

        return [
            'name'                  => $this->name,

            'church_name'           => $this->church->name,

            'user_id'               => $this->id,

            'fullname'              => $this->FullName,

            'firstname'             => $this->userprofile->firstname,

            'lastname'              => optional($this->userprofile)->lastname=="" ? null:optional($this->userprofile)->lastname ,

            'birth_firstname'       => optional($this->userprofile)->birth_firstname=="" ? null:optional($this->userprofile)->birth_firstname,

            'birth_lastname'        => optional($this->userprofile)->birth_lastname=="" ? null:optional($this->userprofile)->birth_lastname,

            'gender'                => $this->userprofile->gender,

            'date_of_birth'         => date('d-m-Y',strtotime(optional($this->userprofile)->date_of_birth)),

            'was_baptized'          => optional($this->userprofile)->was_baptized=="" ? null:optional($this->userprofile)->was_baptized,

            'baptism_date'          => optional($this->userprofile)->baptism_date=="" ? null:date('d-m-Y',strtotime(optional($this->userprofile)->baptism_date)),

            'profession'            => $this->userprofile->profession,


            'profession_display'    => ucwords(str_replace('_', ' ', optional($this->userprofile)->profession)),

            'sub_occupation'        => $this->userprofile->sub_occupation,

            'address'               => $this->userprofile->address,

            'city'                  => $this->userprofile->city->name,

            'state'                 => $this->userprofile->state->name,

            'country'               => $this->userprofile->country->name,

            'pincode'               => optional($this->userprofile)->pincode=="" ? null:optional($this->userprofile)->pincode,

            'email'                 => $this->email,

            'mobile_no'             => $this->mobile_no,

            'membership_type'       => $this->userprofile->membership_type,

            'membership_start_date' => optional($this->userprofile)->membership_start_date=="" ? null:date('d-m-Y',strtotime(optional($this->userprofile)->membership_start_date)),

            'family'                => optional($this->userprofile)->family=="" ? null:optional($this->userprofile)->family,

            'marriage_status'       => optional($this->userprofile)->marriage_status=="" ? null:optional($this->userprofile)->marriage_status,

            'marriage_date'         => optional($this->userprofile)->marriage_start_date=="" ? null:date('d-m-Y',strtotime(optional($this->userprofile)->marriage_start_date)),

            'relation'              => optional($this->userprofile)->relation=="partner" ? 'Partner':optional($this->userprofile)->relation,

            'notes'                 => optional($this->userprofile)->notes=="" ? null:optional($this->userprofile)->notes,

            'avatar'                => $avatar,

            'created_at'            => optional($this->userprofile)->created_at=="" ? null:date('d-m-Y H:i:s',strtotime(optional($this->userprofile)->created_at)),

            'updated_at'            => optional($this->userprofile)->updated_at=="" ? null:date('d-m-Y H:i:s',strtotime(optional($this->userprofile)->updated_at)),

            'age'                   => date('Y')-date('Y',strtotime(optional($this->userprofile)->date_of_birth)),

            'ref_id'                => $this->ref_id,

            'email_verified'        => $email_verified,

            'referby'               => $this->refer,
        ];
    }
}
