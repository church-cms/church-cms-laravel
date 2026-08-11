<?php

namespace App\Observers;

use App\Models\GetResponse;
use App\Models\MailQueue;
use Exception;
use Log;

class MailQueueObserver
{
    /**
     * Handle the mail queue "created" event.
     *
     * @param  \App\Models\MailQueue  $mailQueue
     * @return void
     */
    public function created(MailQueue $mailQueue)
    {
        //
        try
        {
            $getresponse=GetResponse::where([['campaign_id',$mailQueue->campaign_id],['status',1]])->get();
            foreach($getresponse as $response)
            {
                $day=$response->day_after;
                    //->whereNull('fired_at') - if jobs run we can add
                MailQueue::where('campaign_id',$response->campaign_id)->update(['rule_checked_at' => \DB::raw('DATE_ADD(scheduled_at, INTERVAL '.$day.' DAY)')]);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }        
    }

    /**
     * Handle the mail queue "updated" event.
     *
     * @param  \App\Models\MailQueue  $mailQueue
     * @return void
     */
    public function updated(MailQueue $mailQueue)
    {
        //
    }

    /**
     * Handle the mail queue "deleted" event.
     *
     * @param  \App\Models\MailQueue  $mailQueue
     * @return void
     */
    public function deleted(MailQueue $mailQueue)
    {
        //
    }

    /**
     * Handle the mail queue "restored" event.
     *
     * @param  \App\Models\MailQueue  $mailQueue
     * @return void
     */
    public function restored(MailQueue $mailQueue)
    {
        //
    }

    /**
     * Handle the mail queue "force deleted" event.
     *
     * @param  \App\Models\MailQueue  $mailQueue
     * @return void
     */
    public function forceDeleted(MailQueue $mailQueue)
    {
        //
    }
}