<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\EventManager;

class UserDetail extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {


        $attendance_count = EventManager::where('user_id', $this->id)->get();

        if (count($attendance_count) > 0) {
            $attendance_status = 1;
        } else {
            $attendance_status = 0;
        }


        return [

            //'name'                  => $this->name,

            'church_name'           => $this->church->name,

            'user_id'               => $this->id,

            'firstname'             => $this->userprofile->firstname ?? '',

            'lastname'              => optional($this->userprofile)->lastname ?? '',

            'birth_firstname'       => optional($this->userprofile)->birth_firstname ?? '',

            'birth_lastname'        => optional($this->userprofile)->birth_lastname ?? '',

            'gender'                => optional($this->userprofile)->gender ?? '',

            'date_of_birth'         => date('d-m-Y', strtotime($this->userprofile->date_of_birth)),

            /*'was_baptized'          => optional($this->userprofile)->was_baptized,

            'baptism_date'          => optional($this->userprofile)->baptism_date=="" ? null:date('d-m-Y',strtotime(optional($this->userprofile)->baptism_date)),*/

            'profession'            => $this->userprofile->profession ?? '',

            'sub_occupation'        => $this->userprofile->sub_occupation ?? '',

            'address'               => $this->userprofile->address ?? '',

            'city_name'             => $this->userprofile->city_id == null ? '' : $this->userprofile->city->name,

            'state_name'            => $this->userprofile->state_id == null ? '' : $this->userprofile->state->name,

            'country_name'          => $this->userprofile->country_id == null ? '' : $this->userprofile->country->name,

            'city'                => $this->userprofile->city_id == null ? '' : $this->userprofile->city_id,

            'state'               => $this->userprofile->state_id == null ? '' : $this->userprofile->state_id,

            'country'             => $this->userprofile->country_id == null ? '' : $this->userprofile->country_id,

            'pincode'               => optional($this->userprofile)->pincode == null ? '' : $this->userprofile->pincode,

            'email_id'              => $this->email == null ? '' : $this->email,

            'mobile_no'             => $this->mobile_no == null ? '' : $this->mobile_no,

            'aadhar_number'         => $this->userprofile->aadhar_number ?? '',

            'membership_type'       => $this->userprofile->membership_type,

            'membership_start_date' => optional($this->userprofile)->membership_start_date == "" ? '' : date('d-m-Y', strtotime(optional($this->userprofile)->membership_start_date)),

            /*'membership_end_date' => optional($this->userprofile)->membership_end_date=="" ? null:date('d-m-Y',strtotime(optional($this->userprofile)->membership_end_date)),*/

            'family'                => optional($this->userprofile)->family ?? '',

            'marriage_status'       => optional($this->userprofile)->marriage_status == null ? '' : optional($this->userprofile)->marriage_status,

            'marriage_date'         => optional($this->userprofile)->marriage_start_date == "" ? '' : date('d-m-Y', strtotime(optional($this->userprofile)->marriage_start_date)),
            /*
            'marriage_end_date' => optional($this->userprofile)->marriage_end_date=="" ? null:date('d-m-Y',strtotime(optional($this->userprofile)->marriage_end_date)),*/

            'relation'              => optional($this->userprofile)->relation ?? '',

            'notes'                 => optional($this->userprofile)->notes ?? '',

            'avatar'                => $this->userprofile->AvatarPath ?? '',

            'attendance_status' => $attendance_status
        ];
    }
}
