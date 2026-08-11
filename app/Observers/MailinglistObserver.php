<?php

namespace App\Observers;

use App\Events\CampaignDeleteEvent;
use App\Models\MailingList;
use Exception;
use Log;

class MailinglistObserver
{

    public function creating(MailingList $mailinglist)
    {
        try
        {
            $slugcreate = strtolower($mailinglist->name);
            $new_slug = str_replace(' ', '-', $slugcreate);
            $mailinglist->slug=$new_slug;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the mailinglist "created" event.
     *
     * @param  \App\Models\MailingList  $mailinglist
     * @return void
     */
    public function created(MailingList $mailinglist)
    {
        //
    }

    /**
     * Handle the mailinglist "updated" event.
     *
     * @param  \App\Models\MailingList  $mailinglist
     * @return void
     */
    public function updated(MailingList $mailinglist)
    {
        //
    }

    /**
     * Handle the mailinglist "deleted" event.
     *
     * @param  \App\Models\MailingList  $mailinglist
     * @return void
     */
    public function deleted(MailingList $mailinglist)
    {
        //
        try
        {
            $campaign=\App\Models\Campaign::where('mailinglist_id',$mailinglist->id)->first();
            $campaign->emails()->detach();
            $campaign->delete();
            $mailinglist->subscribers()->detach();
         
            $data=array();
            event(new CampaignDeleteEvent($campaign,$data));
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the mailinglist "restored" event.
     *
     * @param  \App\Models\MailingList  $mailinglist
     * @return void
     */
    public function restored(MailingList $mailinglist)
    {
        //
    }

    /**
     * Handle the mailinglist "force deleted" event.
     *
     * @param  \App\Models\MailingList  $mailinglist
     * @return void
     */
    public function forceDeleted(MailingList $mailinglist)
    {
        //
    }
}