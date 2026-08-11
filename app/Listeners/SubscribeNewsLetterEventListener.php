<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\SubscribeNewsLetterEvent;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\NewsletterProcess;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\User;

class SubscribeNewsLetterEventListener implements ShouldQueue
{
    use NewsletterProcess;
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
     * @param  SubscribeNewsLetterEvent  $event
     * @return void
     */
    public function handle(SubscribeNewsLetterEvent $event)
    {
        //
        for($i = 0 ; $i < $event->request->count ; $i++)
        { 
            $user_id = 'user'.$i;
            $user = User::where([['usergroup_id',5],['id',$event->request->$user_id]])->first();
            $send = $this->subscribeNewsletter($event->request , $event->church_id , $user , $event->admin);
        }
    }
}