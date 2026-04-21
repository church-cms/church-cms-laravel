<?php

namespace App\Listeners\Notification;

use App\Events\Notification\PrayerNotificationEvent;
use App\Notifications\NewMessageNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use Notification;

class PrayerNotificationEventListener implements ShouldQueue
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
     * @param  PrayerNotificationEvent  $event
     * @return void
     */
    public function handle(PrayerNotificationEvent $event)
    {
        //
        $users=User::where('church_id',$event->data['church_id'])->where('id','!=',$event->data['user_id'])->get();
        foreach($users as $user)
        {
          Notification::send($user, new NewMessageNotification($event->data['details']));
        }
    }
}
