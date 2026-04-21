<?php

namespace App\Listeners;

use App\Events\VerificationMailEvent;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;

class VerificationMailEventListener
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
     * @param  VerificationMailEvent  $event
     * @return void
     */
    public function handle(VerificationMailEvent $event)
    {
        //
        //dump($event->user->email);
        Mail::to($event->user->email)->queue(new EmailVerification($event->user));
    }
}
