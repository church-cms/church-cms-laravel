<?php

namespace App\Http\Resources\API\Guest;

use Illuminate\Http\Resources\Json\JsonResource;

class PrayerRequest extends JsonResource
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
            'id'                         => $this->id,
            'requested_person'           => $this->submitter_name,
            'requested_person_avatar'    => $this->user ? $this->user->userprofile->AvatarPath : null,
            'category'                   => $this->category ? $this->category->display_name : null,
            'text'                       => $this->text,
            'status'                     => $this->status,
            'total_prayers'              => $this->total_participant_count,
            'date'                       => $this->created_at->format('d M Y h:i A'),
        ];
    }
}
