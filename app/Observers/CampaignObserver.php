<?php

namespace App\Observers;

use App\Events\CampaignDeleteEvent;
use App\Traits\EmailQueueProcess;
use App\Models\MailingList;
use App\Models\Campaign;
use Exception;
use Log;

class CampaignObserver
{
    use EmailQueueProcess;
    
    /**
     * Handle the campaign "created" event.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return void
     */
    public function created(Campaign $campaign)
    {
        //
        try
        {
            $name = $campaign->name. " Mailing list";
            $exists = MailingList::where('name',$name)->exists();
           
            if(!$exists)
            {
                $newMailinglist = MailingList::create([ 
                    'church_id'     =>  $campaign->church_id,
                    'scope'         =>  'campaign',
                    'name'          =>  $name,
                    'description'   =>  "Auto created mail list for ".$campaign->name,
                    'is_published'  =>  1
                ]);

                $campaign->mailinglist()->associate($newMailinglist);

                $campaign->save();       
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the campaign "updated" event.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return void
     */
    public function updated(Campaign $campaign)
    {
        //
    }

    /**
     * Handle the campaign "deleted" event.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return void
     */
    public function deleted(Campaign $campaign)
    {
        //
        $campaign->emails()->detach();
        $data = array();
        event(new CampaignDeleteEvent($campaign,$data));
        //EmailQueueProcess::deleteEmailQueueforCampaign($campaign,$data);
    }

    /**
     * Handle the campaign "restored" event.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return void
     */
    public function restored(Campaign $campaign)
    {
        //
    }

    /**
     * Handle the campaign "force deleted" event.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return void
     */
    public function forceDeleted(Campaign $campaign)
    {
        //
    }
}