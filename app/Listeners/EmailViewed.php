<?php

namespace App\Listeners;

use jdavidbakr\MailTracker\Events\ViewEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\MailQueue;
class EmailViewed
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
     * @param  ViewEmailEvent  $event
     * @return void
     */
    public function handle(ViewEmailEvent $event)
    {
        //
       // dump($event->sent_email->id);
         $model_id = $event->sent_email->getHeader('X-Model-ID');
         $mailqueue=MailQueue::where('id',$model_id)->first();
         if(count($mailqueue)>0){
            if($mailqueue->is_read==0)
            {
                $update=[
                    'is_read'=>1
                ];
                MailQueue::where('id',$model_id)->update($update);
            }
            \DB::table('mail_queues')->where('id',$model_id)->increment('clicks');
         }
    }
}
