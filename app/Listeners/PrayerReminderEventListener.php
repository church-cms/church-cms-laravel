<?php

namespace App\Listeners;

use App\Events\PrayerReminderEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Traits\ReminderProcess;
use App\Models\User;

class PrayerReminderEventListener implements ShouldQueue
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
     * @param  PrayerReminderEvent  $event
     * @return void
     */
    public function handle(PrayerReminderEvent $event)
    {
        //
        if($event->via =='mail')
        {
            $users=User::where([['church_id',$event->church_id],['id','!=',$event->user_id]])->whereNotNull('email')->ByRole('5')->pluck('email')->toArray();
        }
        elseif($event->via == 'sms')
        {
            $users=User::where([['church_id',$event->church_id],['id','!=',$event->user_id]])->whereNotNull('mobile_no')->ByRole('5')->pluck('mobile_no')->toArray();
        }
        else
        {
            $users=User::where([['church_id',$event->church_id],['id','!=',$event->user_id]])->whereNotNull('email')->ByRole('5')->pluck('email')->toArray();
        }
         
        foreach ($users as $user) 
        {
            $this->createReminder($event->church_id,$event->from,$user,$event->subject,$event->message,$event->entity_id,$event->entity_name,$event->via,$event->data,$event->executed_at);
        }
    }
}
