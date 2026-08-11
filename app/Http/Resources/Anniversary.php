<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Anniversary extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->user->ref_id == null)
        {
            $husbandName        = ucwords($this->user->FullName);
            $husbandUserName    = ucwords($this->user->name);
            $husbandAvatar      = $this->AvatarPath;
            $wifeName           = ucwords($this->user->members[0]['FullName']);
            $wifeUserName       = ucwords($this->user->members[0]['name']);
            $wifeAvatar         = $this->user->members[0]['userprofile']['AvatarPath'];
        }
        else
        {
            return 'no data';
        }
        return [
            'husbandName'    => $husbandName,
            'husbandUserName'=> $husbandUserName,
            'wifeUserName'   => $wifeUserName,
            'husbandAvatar'  => $husbandAvatar,
            'wifeName'       => $wifeName,
            'wifeAvatar'     => $wifeAvatar,  
       ];
    }
}