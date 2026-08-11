<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;
use App\Models\Events;
use Exception;
use Log;

class EventObserver
{
    /**
     * Handle the events "created" event.
     *
     * @param  \App\Models\Events  $events
     * @return void
     */
    public function created(Events $events)
    {
        //
        try
        {
            $update=[
                'created_by'=>Auth::id(),
                'updated_by'=>Auth::id(),
            ];

            Events::where('id',$events->id)->update($update);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the events "updated" event.
     *
     * @param  \App\Models\Events  $events
     * @return void
     */
    public function updated(Events $events)
    {
        //
        try
        {
            $update=[
                'updated_by'=>Auth::id(),
            ];
            Events::where('id',$events->id)->update($update);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the events "deleted" event.
     *
     * @param  \App\Models\Events  $events
     * @return void
     */
    public function deleted(Events $events)
    {
        //
    }

    /**
     * Handle the events "restored" event.
     *
     * @param  \App\Models\Events  $events
     * @return void
     */
    public function restored(Events $events)
    {
        //
    }

    /**
     * Handle the events "force deleted" event.
     *
     * @param  \App\Models\Events  $events
     * @return void
     */
    public function forceDeleted(Events $events)
    {
        //
    }
}