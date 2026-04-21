<?php

namespace App\Listeners;

use App\Events\ReminderMailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderMail;

class ReminderMailListener implements ShouldQueue
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
     * @param  ReminderMailEvent  $event
     * @return void
     */
    public function handle(ReminderMailEvent $event)
    {
        //dd($event);

  /*   $user = User::where('church_id',$events->church_id)->get();
     $this->sendNotification("Test", "Test Notification", optional($user->platform_token));*/

        Mail::to($event->queue->to)->queue(new ReminderMail($event->queue));
    }
}
