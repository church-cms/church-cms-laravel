<?php

namespace App\Listeners;

use App\Events\ImportSubscriberEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Subscribers;

class ImportSubscriberListener implements ShouldQueue
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
     * @param  ImportSubscriberEvent  $event
     * @return void
     */
    public function handle(ImportSubscriberEvent $event)
    {
        //dump($event);
        $sub_update=Subscribers::whereIn('id',$event->subscriber_id)->update($event->update);
        
    }
}
