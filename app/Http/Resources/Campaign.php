<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\CampaignEmail;
class Campaign extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $campaignemail=CampaignEmail::where('campaign_id',$this->id)->count();
        return 
        [
            //
            'id'           =>  $this->id,
            'name'         =>  $this->name,
            'description'  =>  $this->description,
            'status'       =>  $this->status,
            'tick'         =>  $this->getFilePath('uploads/static/tick.svg'),
            'cross'        =>  $this->getFilePath('uploads/static/close.svg'),
            'email_count'  => $campaignemail,
          
        ];
    }
}
