<?php

namespace App\Listeners;

use App\Events\AfterExpiredEvent;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionExpiredMail;

class AfterExpiredListener
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
     * @param  AfterExpiredEvent  $event
     * @return void
     */
    public function handle(AfterExpiredEvent $event)
    {
        //
        Mail::to($event->queue->user->email)->queue(new SubscriptionExpiredMail($event->queue));
    }
}
