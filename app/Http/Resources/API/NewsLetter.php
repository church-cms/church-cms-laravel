<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class NewsLetter extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = User::where('name',$this->name)->first();
        return 
        [
            'name'      =>  $user->FullName,
            'email'     =>  $this->email,
            'status'    =>  $this->status,
        ];
    }
}