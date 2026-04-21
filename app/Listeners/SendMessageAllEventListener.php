<?php

namespace App\Listeners;

use App\Events\SendMessageAllEvent;
use App\Traits\SendMessageProcess;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\User;
use Illuminate\Support\Str;
class SendMessageAllEventListener 
{
    use SendMessageProcess;
    use LogActivity;
    use Common;

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
     * @param  SendMessageEvent  $event
     * @return void
     */
    public function handle(SendMessageAllEvent $event)
    {
       // dump($event->data);
        //

        $batch_id=(string) Str::uuid();
        foreach($event->data->selected as $user_id)
        {


           // dump($user_id);
            $user = User::where([['church_id',$event->church_id],['id',$user_id]])->first();
            $send = $this->sendMessage($event->data , $event->church_id , $event->admin_email , $user , $event->admin,$batch_id);
        }

        /*for($i = 0 ; $i < $event->data->count ; $i++)
        { 
            $user_id = 'user'.$i;
            $user = User::where([['usergroup_id',5],['id',$event->data->$user_id]])->first();
            $send = $this->sendMessage($event->data , $event->church_id , $event->admin_email , $user , $event->admin);
        }*/
    }
}