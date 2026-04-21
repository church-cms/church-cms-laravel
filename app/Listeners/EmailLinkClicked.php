<?php

namespace App\Listeners;

use jdavidbakr\MailTracker\Events\LinkClickedEvent;
class EmailLinkClicked
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
     * @param  LinkClickedEvent  $event
     * @return void
     */
    public function handle(LinkClickedEvent $event)
    {
        /* $model_id = $event->sent_email->getHeader('X-Model-ID');
         //dump($model_id);
         $mailqueue=MailQueue::where('id',$model_id)->first();
         if(count($mailqueue)>0){
           
                $update=[
                    'clicks'=>$event->sent_email->clicks
                ];
                MailQueue::where('id',$model_id)->update($update);
            
         }*/
    }
}
