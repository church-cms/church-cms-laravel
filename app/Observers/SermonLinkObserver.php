<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;
use App\Models\SermonLink;
use Exception;
use Log;

class SermonLinkObserver
{
    /**
     * Handle the sermon link "created" event.
     *
     * @param  \App\Models\SermonLink  $sermonLink
     * @return void
     */
    public function created(SermonLink $sermonLink)
    {
        //
        try
        {
            $update=[
                'created_by'=>Auth::id(),
                'updated_by'=>Auth::id(),
            ];
            SermonLink::where('id',$events->id)->update($update);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the sermon link "updated" event.
     *
     * @param  \App\Models\SermonLink  $sermonLink
     * @return void
     */
    public function updated(SermonLink $sermonLink)
    {
        //
        try
        {
            $update=[
                'updated_by'=>Auth::id(),
            ];
            SermonLink::where('id',$events->id)->update($update);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the sermon link "deleted" event.
     *
     * @param  \App\Models\SermonLink  $sermonLink
     * @return void
     */
    public function deleted(SermonLink $sermonLink)
    {
        //
    }

    /**
     * Handle the sermon link "restored" event.
     *
     * @param  \App\Models\SermonLink  $sermonLink
     * @return void
     */
    public function restored(SermonLink $sermonLink)
    {
        //
    }

    /**
     * Handle the sermon link "force deleted" event.
     *
     * @param  \App\Models\SermonLink  $sermonLink
     * @return void
     */
    public function forceDeleted(SermonLink $sermonLink)
    {
        //
    }
}