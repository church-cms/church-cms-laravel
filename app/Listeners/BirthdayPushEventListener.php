<?php

namespace App\Listeners;

use App\Events\BirthdayPushEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Traits\SendPushNotification;

class BirthdayPushEventListener implements ShouldQueue
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
     * @param  BirthdayPushEvent  $event
     * @return void
     */
    public function handle(BirthdayPushEvent $event)
    {
        //
        $this->sendNotification($event->queue->data, $event->queue->user->platform_token);
    }
}
