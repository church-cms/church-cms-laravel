<?php

namespace App\Listeners\Notification;

use App\Events\Notification\PushNotificationEvent;
use App\Notifications\NewMessageNotification;
use App\Models\User;
use Notification;

class PushNotificationEventListener
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
     * @param  PushNotificationEvent  $event
     * @return void
     */
    public function handle(PushNotificationEvent $event)
    {
        //
        $users=User::where('church_id',$event->data['church_id'])->get();
        foreach($users as $user)
        {
          Notification::send($user, new NewMessageNotification($event->data['details']));
        }
    }
}
