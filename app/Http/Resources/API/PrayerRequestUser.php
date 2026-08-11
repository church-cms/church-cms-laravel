<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class PrayerRequestUser extends JsonResource
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
            'id'             => $this->id,
            'avatar'         => $this->user ? $this->user->userprofile->AvatarPath : null,
            'category'       => $this->category ? $this->category->display_name : null,
            'text'           => $this->text,
            'status'         => $this->status,
            'display_status' => $this->status_label,
            'total_prayers'  => $this->total_participant_count,
            'date'           => $this->created_at->format('d-m-Y h:i A'),
        ];
    }
}
