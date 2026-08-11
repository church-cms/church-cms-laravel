<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;
use App\Models\Gallery;
use Exception;
use Log;

class GalleryObserver
{
    /**
     * Handle the gallery "created" event.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return void
     */
    public function created(Gallery $gallery)
    {
        //
        try
        {
            $update=[
                'created_by'=>Auth::id(),
                'updated_by'=>Auth::id(),
            ];

            Gallery::where('id',$gallery->id)->update($update);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the gallery "updated" event.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return void
     */
    public function updated(Gallery $gallery)
    {
        //
        try
        {
            $update=[
                'updated_by'=>Auth::id(),
            ];
            
            Gallery::where('id',$gallery->id)->update($update);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the gallery "deleted" event.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return void
     */
    public function deleted(Gallery $gallery)
    {
        //
    }

    /**
     * Handle the gallery "restored" event.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return void
     */
    public function restored(Gallery $gallery)
    {
        //
    }

    /**
     * Handle the gallery "force deleted" event.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return void
     */
    public function forceDeleted(Gallery $gallery)
    {
        //
    }
}