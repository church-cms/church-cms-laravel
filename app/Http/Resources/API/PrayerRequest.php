<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Models\PrayerParticipant;

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
        $userId = Auth::id();

        $hasLifted = PrayerParticipant::where('prayer_id', $this->id)
            ->where('user_id', $userId)
            ->exists();

        return [
            'id'                         => $this->id,
            'requested_person'           => $this->submitter_name,
            'requested_person_avatar'    => $this->user ? $this->user->userprofile->AvatarPath : null,
            'category'                   => $this->category ? $this->category->display_name : null,
            'text'                       => $this->text,
            'status'                     => $this->status,
            'status_label'               => $this->status_label,
            'response_status'            => $hasLifted ? 1 : 0,
            'member_count'               => $this->member_count,
            'guest_count'                => $this->guest_count,
            'anonymous_count'            => $this->anonymous_count,
            'total_prayers'              => $this->total_participant_count,
            'days_remaining'             => $this->days_remaining,
            'expires_at'                 => $this->expires_at ? $this->expires_at->toIso8601String() : null,
            'is_pinned'                  => $this->is_pinned,
            'date'                       => $this->created_at->format('d-m-Y h:i A'),
        ];
    }
}
