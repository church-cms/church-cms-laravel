<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class GroupLink extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $members = GroupLink::where([
            ['church_id', Auth::user()->church_id],
            ['user_id', '!=', Auth::id()],
            ['group_id', $this->group_id]
        ])->get();




        $grouplinks = GroupLink::where([
            ['church_id', Auth::user()->church_id],
            ['user_id', '=', Auth::id()],
            ['group_id', $this->group_id]
        ])->first();


        $group_members = [];

        foreach ($members as $member) {
            $group_members[] = $member->user->FullName;
        }

        $permissions = $this->user->permissionUser;
        $permission_name = [];
        foreach ($permissions as $permission) {
            $permission_name[] = $permission->permission->display_name;
        }

        return
            [
                'group_id'          => $this->group->id,
                'group_name'        => $this->group->name,
                'cover_image'       => $this->group->CoverImagePath,
                'started'           => date('M-Y', strtotime($this->group->created_at)),
                'group_category'    => $this->group->groupCategory->name,
                'group_type'        => $this->group->group_type,
                'group_description' => $this->group->description,
                'user_permissions'  => $permission_name,
                'group_members'     => $group_members,
                'role' => $grouplinks->role

            ];
    }
}
