<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupPost extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $path = '';
        if ($this->attachments && $this->attachment_type === 'image') {
            $path = asset('storage/' . $this->attachments);
        }

        return [
            'id'            =>  $this->id,
            'message'       =>  $this->message,
            'attachments'   =>  $path,
            'status'   => $this->status,
            'created_at'   => $this->created_at->diffForHumans()
        ];
    }
}
