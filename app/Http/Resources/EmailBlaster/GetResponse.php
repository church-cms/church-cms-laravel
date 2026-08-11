<?php

namespace App\Http\Resources\EmailBlaster;

use Illuminate\Http\Resources\Json\JsonResource;

class GetResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            //
            'id'                            =>  $this->id,
            'campaign_id'                   =>  $this->campaign_id,
            'campaign_name'                 =>  $this->campaign->name,
            'name'                          =>  $this->name,
            'email_open_campaign_id'        =>  $this->email_open_campaign_id,
            'email_open_campaign_name'      =>  $this->emailOpenCampaign->name,
            'no_email_open_campaign_id'     =>  $this->no_email_open_campaign_id,
            'no_email_open_campaign_name'   =>  $this->noEmailOpenCampaign->name,
            'day_after'                     =>  $this->day_after,
            'status'                        =>  $this->status,
        ];
    }
}