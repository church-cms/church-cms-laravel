<?php

namespace App\Listeners;

use App\Events\SinglePushEvent;
use App\Traits\SendPushNotification;
use App\Models\User;

class SinglePushEventListener // implements ShouldQueue
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
     * @param  SinglePushEvent  $event
     * @return void
     */
    public function handle(SinglePushEvent $event)
    {
        //
        $user=User::where([
            ['church_id',$event->data['church_id']],
            ['id',$event->data['user_id']]
        ])->whereNotNull('platform_token')->first();

        if($user != '')
        {
            $this->sendNotification($event->data,$user->platform_token);
        }
    }

}
