<?php

namespace App\Listeners;

use App\Events\AttendanceEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Traits\AttendanceProcess;
use App\Models\User;

class AttendanceEventListener implements ShouldQueue
{
    use AttendanceProcess;

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
     * @param  AttendanceEvent  $event
     * @return void
     */
    public function handle(AttendanceEvent $event)
    {
        //
        $users=User::where('church_id',$event->church_id)->ByRole('5')->pluck('id')->toArray();

        foreach ($users as $user_id) 
        {
            $this->createAttendance($event->church_id,$user_id,$event->entity_id,$event->entity_name,$event->title,$event->category,$event->date);
        }
    }
}
