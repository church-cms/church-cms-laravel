<?php

namespace App\Observers;

use App\Models\GetResponse;
use App\Models\MailQueue;
use Exception;
use Log;

class GetResponseObserver
{
    /**
     * Handle the get response "created" event.
     *
     * @param  \App\Models\GetResponse  $getResponse
     * @return void
     */
    public function created(GetResponse $getResponse)
    {
        //
        try
        {
            $day=$getResponse->day_after;

            MailQueue::where('campaign_id',$getResponse->campaign_id)->whereNull('fired_at')->update(['getResponse_checked_at' => \DB::raw('DATE_ADD(scheduled_at, INTERVAL '.$day.' DAY)')]);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the get response "updated" event.
     *
     * @param  \App\Models\GetResponse  $getResponse
     * @return void
     */
    public function updated(GetResponse $getResponse)
    {
        //
    }

    /**
     * Handle the get response "deleted" event.
     *
     * @param  \App\Models\GetResponse  $getResponse
     * @return void
     */
    public function deleted(GetResponse $getResponse)
    {
        //
    }

    /**
     * Handle the get response "restored" event.
     *
     * @param  \App\Models\GetResponse  $getResponse
     * @return void
     */
    public function restored(GetResponse $getResponse)
    {
        //
    }

    /**
     * Handle the get response "force deleted" event.
     *
     * @param  \App\Models\GetResponse  $getResponse
     * @return void
     */
    public function forceDeleted(GetResponse $getResponse)
    {
        //
    }
}