<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;
use App\Models\MediaFile;
use Exception;
use Log;

class MediaFileObserver
{
    /**
     * Handle the mediafile "created" event.
     *
     * @param  \App\Models\MediaFile  $mediafile
     * @return void
     */
    public function created(MediaFile $mediafile)
    {
        //
        try
        {
            $update=[
                'created_by'=>Auth::id(),
                'updated_by'=>Auth::id(),
            ];

            MediaFile::where('id',$mediafile->id)->update($update);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the mediafile "updated" event.
     *
     * @param  \App\Models\MediaFile  $mediafile
     * @return void
     */
    public function updated(MediaFile $mediafile)
    {
        //
        try
        {
            $update=[
                'updated_by'=>Auth::id(),
            ];
            
            MediaFile::where('id',$mediafile->id)->update($update);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the mediafile "deleted" event.
     *
     * @param  \App\Models\MediaFile  $mediafile
     * @return void
     */
    public function deleted(MediaFile $mediafile)
    {
        //
    }

    /**
     * Handle the mediafile "restored" event.
     *
     * @param  \App\Models\MediaFile  $mediafile
     * @return void
     */
    public function restored(MediaFile $mediafile)
    {
        //
    }

    /**
     * Handle the mediafile "force deleted" event.
     *
     * @param  \App\Models\MediaFile  $mediafile
     * @return void
     */
    public function forceDeleted(MediaFile $mediafile)
    {
        //
    }
}