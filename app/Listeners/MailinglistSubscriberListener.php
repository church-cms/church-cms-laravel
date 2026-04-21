<?php

namespace App\Listeners;

use App\Events\MailinglistSubscriberEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Traits\EmailQueueProcess;

class MailinglistSubscriberListener implements ShouldQueue
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
     * @param  MailinglistSubscriberEvent  $event
     * @return void
     */
    public function handle(MailinglistSubscriberEvent $event)
    {//dump('MailinglistSubscriberEventlistener');
       EmailQueueProcess::createEmailQueue($event->MailinglistSubscriber,$event->data);
    }
}
