<?php

namespace App\Listeners;

use App\Events\SendMessageEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMessageMail;
use App\Models\SendMail;

class SendMessageEventListener implements ShouldQueue
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
     * @param  SendMailEvent  $event
     * @return void
     */
    public function handle(SendMessageEvent $event)
    {
        //
       // dump($event->sendMail->church_id);
        //$now = Carbon::now();
        //$sendMailz = SendMail::where([['church_id',$event->sendMail->church_id],['created_at',$now]])->get();
         $sendMailz = SendMail::where([['church_id',$event->sendMail->church_id],['id',$event->sendMail->id]])->first();
          Mail::to($sendMailz->to)->queue(new SendMessageMail($sendMailz));
         //dd($sendMailz);
        /*foreach ($sendMailz as $sendMail) 
        {
            dump("hi");
            Mail::to($sendMail->to)->queue(new SendMessageMail($sendMail));
        }*/
    }
}
