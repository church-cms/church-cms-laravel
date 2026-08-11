<?php

namespace App\Listeners;

use App\Events\AdminBirthdayReminderEvent;
use App\Traits\ReminderProcess;

class AdminBirthdayReminderEventListener
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
     * @param  AdminBirthdayReminderEvent  $event
     * @return void
     */
    public function handle(AdminBirthdayReminderEvent $event)
    {
        //
        if($event->via =='mail')
        {
            $this->createReminder($event->church_id,$event->from,$event->mail,$event->subject,$event->message,$event->entity_id,$event->entity_name,$event->via,$event->data,$event->executed_at);
        }
        elseif($event->via == 'sms')
        {
            $this->createReminder($event->church_id,$event->from,$event->mobile_no,$event->subject,$event->message,$event->entity_id,$event->entity_name,$event->via,$event->data,$event->executed_at);
        }
        else
        {
            $this->createReminder($event->church_id,$event->from,$event->mail,$event->subject,$event->message,$event->entity_id,$event->entity_name,$event->via,$event->data,$event->executed_at);
        }      
    }
}
