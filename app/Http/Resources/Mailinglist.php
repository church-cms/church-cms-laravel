<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\CampaignEmail;

class Mailinglist extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return 
        [
            //
            'id'                =>  $this->id,
            'name'              =>  $this->name,
            'description'       =>  $this->description,
            'is_published'      =>  $this->is_published,
            'total_subscribers' =>  $this->totalactivesubscribers($this->id), 
        ];
    }
}