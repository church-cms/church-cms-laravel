<?php

namespace App\Observers;

use App\Models\ChurchDetail;
use Illuminate\Support\Str;
use App\Models\Church;
use Exception;
use Log;

class ChurchObserver
{
    /**
     * Handle the church "created" event.
     *
     * @param  \App\Models\Church  $church
     * @return void
     */
    public function created(Church $church)
    {
        //
        try
        {
            $keys = ['church_logo' , 'short_summary' , 'long_summary' , 'quotes' , 'phone' , 'email' , 'address' , 'latitude' , 'longitude' , 'website' , 'facebook' , 'twitter' , 'instagram','site_title','site_description','site_keyword','favicon'];

            foreach ($keys as $key) 
            {
                $detail = ChurchDetail::create([
                    'church_id'     =>  $church->id,
                    'meta_key'      =>  $key,
                    'meta_value'    =>  "-",
                ]);
            }

        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the church "updated" event.
     *
     * @param  \App\Models\Church  $church
     * @return void
     */
    public function updated(Church $church)
    {
        //
    }

    /**
     * Handle the church "deleted" event.
     *
     * @param  \App\Models\Church  $church
     * @return void
     */
    public function deleted(Church $church)
    {
        //
    }

    /**
     * Handle the church "restored" event.
     *
     * @param  \App\Models\Church  $church
     * @return void
     */
    public function restored(Church $church)
    {
        //
    }

    /**
     * Handle the church "force deleted" event.
     *
     * @param  \App\Models\Church  $church
     * @return void
     */
    public function forceDeleted(Church $church)
    {
        //
    }
}