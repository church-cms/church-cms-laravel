<?php

namespace App\Listeners;

use jdavidbakr\MailTracker\Events\ComplaintMessageEvent;

class EmailComplaint
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
     * @param  ComplaintMessageEvent  $event
     * @return void
     */
    public function handle(ComplaintMessageEvent $event)
    {
        //
           //dump($event->sent_email->id);
    }
}
