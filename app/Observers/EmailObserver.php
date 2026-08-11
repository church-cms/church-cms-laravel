<?php

namespace App\Observers;

use App\Models\Email;
use Exception;
use Log;

class EmailObserver
{
    /**
     * Handle the email "created" event.
     *
     * @param  \App\Models\Email  $email
     * @return void
     */
    public function created(Email $email)
    {
        //
        try
        {
            $array = ['firstname','lastname','unsubscribelink'];
            
            $update=[
                'variables' => $array,
            ];

            Email::where('id',$email->id)->update($update);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the email "updated" event.
     *
     * @param  \App\Models\Email  $email
     * @return void
     */
    public function updated(Email $email)
    {
        //
    }

    /**
     * Handle the email "deleted" event.
     *
     * @param  \App\Models\Email  $email
     * @return void
     */
    public function deleted(Email $email)
    {
        //
    }

    /**
     * Handle the email "restored" event.
     *
     * @param  \App\Models\Email  $email
     * @return void
     */
    public function restored(Email $email)
    {
        //
    }

    /**
     * Handle the email "force deleted" event.
     *
     * @param  \App\Models\Email  $email
     * @return void
     */
    public function forceDeleted(Email $email)
    {
        //
    }
}