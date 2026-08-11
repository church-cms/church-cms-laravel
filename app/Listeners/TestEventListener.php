<?php

namespace App\Listeners;

use App\Events\TestEvent;
use Illuminate\Support\Facades\Mail;
use App\Models\Invite;
use App\Mail\InvitesMail;

class TestEventListener
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
     * @param  TestEvent  $event
     * @return void
     */
    public function handle(TestEvent $event)
    {
        //
        Mail::to($event->invite->email)->send(new InvitesMail($event->invite));
    }
}
