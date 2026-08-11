<?php

namespace App\Listeners;

use App\Events\SubscriberConfirmEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\EmailQueueProcess;
class SubscriberConfirmListener implements ShouldQueue
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
     * @param  SubscriberConfirmEvent  $event
     * @return void
     */
    public function handle(SubscriberConfirmEvent $event)
    {
        //
       EmailQueueProcess::createEmailQueueafterConfirm($event->mailinglist_id,$event->subscriber_id);
    }
}
