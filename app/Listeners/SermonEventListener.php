<?php

namespace App\Listeners;

use App\Events\SermonEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\SermonMail;
use App\Models\User;

class SermonEventListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SermonEvent  $sermon
     * @return void
     */
    public function handle(SermonEvent $sermon)
    {
        $users=User::where('church_id',$sermon->sermon->church_id)->ByRole('5')->get();

        foreach ($users as $user) 
        {
            if($user->email != null)
            {
                Mail::to($user->email)->queue(new SermonMail($sermon->sermon));
            }
        }
    }
}