<?php

namespace App\Listeners;

use App\Events\PrayerRequestEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Traits\SendPushNotification;
use App\Models\User;

class PrayerRequestEventListener implements ShouldQueue
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
     * @param  PrayerRequestEvent  $event
     * @return void
     */
    public function handle(PrayerRequestEvent $event)
    {
        //
        $users=User::where('church_id',$event->data['church_id'])->where('id','!=',$event->data['user_id'])->whereNotNull('platform_token')->get();
        
        foreach($users as $user)
        {
            $this->sendNotification($event->data,$user->platform_token);
        }
    }
}
