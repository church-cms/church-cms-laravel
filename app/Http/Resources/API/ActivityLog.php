<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Models\GroupLink;

class ActivityLog extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //$userId = Auth::id();

        // $hasLifted = PrayerParticipant::where('prayer_id', $this->id)
        //     ->where('user_id', $userId)
        //     ->exists();

        $name = $this->description;
        $status = '';
        $type = '';
        $type_id = '';

        if ($this->subject_type == 'App\Models\GroupLink') {
            $grouplinks = GroupLink::where('id', $this->subject_id)->first();
            $name = $grouplinks->group->name;
            $groupmember_count = GroupLink::where('group_id', $grouplinks->group_id)->count();
            $description = "Group " . $groupmember_count . ' members active';
            $status = 'active';
            $type = 'group';
            $type_id = $grouplinks->group_id;
        }

        return [
            'id'             => $this->id,
            'name'           => $name,
            'description'    => $description ?? null,
            'status'         => $status ?? null,
            'date'            => $this->created_at->format('d-m-Y h:i A'),
            'type' => $type,
            'type_id' => $type_id
        ];
    }
}
