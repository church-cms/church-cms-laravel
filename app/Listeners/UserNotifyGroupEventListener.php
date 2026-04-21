<?php

namespace App\Listeners;

use App\Events\UserNotifyGroupEvent;
use App\Traits\ReminderProcess;

class UserNotifyGroupEventListener
{
    use ReminderProcess;
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
     * @param  UserNotifyGroupEvent  $event
     * @return void
     */
    public function handle(UserNotifyGroupEvent $event)
    {
        //
        $this->createReminder($event->church_id,$event->from,$event->mobile_no,$event->subject,$event->message,$event->entity_id,$event->entity_name,$event->via,$event->data,$event->executed_at);
    }
}
