<?php

namespace App\Listeners;

use App\Events\CalendarEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\CalendarMail;
use App\Models\User;


class CalendarEventListener implements ShouldQueue
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
     * @param  CalendarEvent  $event
     * @return void
     */
    public function handle(CalendarEvent $events)
    {
      //dd($events);
        $users=User::where('church_id',$events->events->church_id)->ByRole('5')->get();
        //dd($users);
         foreach ($users as $user) 
         {

            Mail::to($user->email)->queue(new CalendarMail($events->events));
            

        }
    }
}
