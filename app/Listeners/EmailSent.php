<?php

namespace App\Listeners;

use jdavidbakr\MailTracker\Events\EmailSentEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\MailQueue;

class EmailSent {
    /**
    * Create the event listener.
    *
    * @return void
    */

    public function __construct() {
        //

    }

    /**
    * Handle the event.
    *
    * @param  EmailSentEvent  $event
    * @return void
    */

    public function handle( EmailSentEvent $event ) {
        //
        $tracker = $event->sent_email;
        $model_id = $event->sent_email->getHeader( 'X-Model-ID' );

        $mailqueue = MailQueue::where( 'id', $model_id )->first();
        if ( count( ( array )$mailqueue )>0 ) {
            if ( $mailqueue->is_read == 0 ) {
                $update = [
                    'status'=>'sent'
                ];
                MailQueue::where( 'id', $model_id )->update( $update );
            }
        }

    }
}
