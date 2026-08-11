<?php

namespace App\Http\Resources\API\Guest;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Models\GroupLink;

class Group extends JsonResource
{

   /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function toArray($request)
    {
        $members = GroupLink::where('group_id',$this->id)->get();
        $group_members = [];
        foreach($members as $member)
        {
            $group_members[] = $member->user->FullName;
        }

        return 
        [
            'group_id'          => $this->id,
            'group_name'        => $this->name,
            'cover_image'       => $this->CoverImagePath,
            'started'           => date('M-Y',strtotime($this->created_at)),
            'group_category'    => $this->groupCategory->name,   
            'group_type'        => $this->group_type,
            'group_description' => $this->description,
            'group_members'     => $group_members,
        ];
    }
}