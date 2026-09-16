<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Models\PrayerParticipant;

class PrayerCategory extends JsonResource
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

        return [
            'id' => $this->id,
            'name'=> $this->name,
            'description'=> $this->description,
            'church_id'=> $this->church_id,
            'date'=> $this->created_at->format('d-m-Y h:i A'),
        ];
    }
}
