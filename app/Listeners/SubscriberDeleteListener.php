<?php

namespace App\Listeners;

use App\Events\SubscriberDeleteEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Traits\EmailQueueProcess;

class SubscriberDeleteListener implements ShouldQueue
{

    use EmailQueueProcess;
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
     * @param  SubscriberDeleteEvent  $event
     * @return void
     */
    public function handle(SubscriberDeleteEvent $event)
    {
       EmailQueueProcess::deleteEmailQueueforSubscribers($event->subscriber,$event->data);
    }
}
