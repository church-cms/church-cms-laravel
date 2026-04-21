<?php

namespace App\Listeners;

use App\Events\PushEvent;
use App\Traits\SendPushNotification;
use App\Models\User;


class PushEventListener //implements ShouldQueue
{

    use SendPushNotification;
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
     * @param  PushEvent $event
     * @return void
     */
    public function handle(PushEvent $event)
    {
      //dump($event);

        
        $users=User::where('church_id',$event->data['church_id'])->whereNotNull('platform_token')->get();
        //dd($users);
        
        foreach($users as $user)
    {
       
        $this->sendNotification($event->data,$user->platform_token);
    }
       // Mail::to($event->queue->to)->queue(new ReminderMail($event->queue));
    }
}
