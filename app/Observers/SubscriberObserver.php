<?php

namespace App\Observers;

use App\Events\SubscriberDeleteEvent;
use Illuminate\Support\Facades\Mail;
use App\Traits\EmailQueueProcess;
use App\Models\Subscribers;
use App\Mail\ListingMail;
use App\Models\Campaign;
use App\Models\Email;
use Exception;
use Log;

class SubscriberObserver
{
    use EmailQueueProcess;
    
    /**
     * Handle the subscriber "created" event.
     *
     * @param  \App\Models\Subscriber  $subscriber
     * @return void
     */
    public function created(Subscribers $subscriber)
    {
        //
        try
        {
            $email=Email::first();
            $campaign=Campaign::first();//rltn
            $url=url('/confirm?email='.$subscriber->email);
            $content=$email->content;
            $content= str_replace(':firstname',$subscriber->firstname,$content);
            $content= str_replace(':logo','https://www.filepicker.io/api/file/SU2YFOjPQzahL7orjBgl',$content);
            $content= str_replace(':url',$url,$content);
            $unsubscribelink=url('/unsubscribe/'.$campaign->mailinglist->slug.'?email='.$subscriber->email);
            $content=str_replace(':unsubscribelink',$unsubscribelink,$content);
            Mail::to($subscriber->email)->queue(new ListingMail($email->subject,$email->from_email,$email->from_name,$email->reply_to_email,$content));
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the subscriber "updated" event.
     *
     * @param  \App\Models\Subscribers  $subscriber
     * @return void
     */
    public function updated(Subscribers $subscriber)
    {
        //
        try
        {
            if($subscriber->is_active==0)
            {
                $subscriber->mailinglist()->detach();
                event(new SubscriberDeleteEvent($subscriber,$data)); 
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }   
    }

    /**
     * Handle the subscriber "deleted" event.
     *
     * @param  \App\Models\Subscribers  $subscriber
     * @return void
     */
    public function deleted(Subscribers $subscriber)
    {
        //
        try
        {
            $data=array();

            event(new SubscriberDeleteEvent($subscriber,$data));
            //EmailQueueProcess::deleteEmailQueueforSubscribers($subscriber,$data);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the subscriber "restored" event.
     *
     * @param  \App\Models\Subscribers  $subscriber
     * @return void
     */
    public function restored(Subscribers $subscriber)
    {
        //
    }

    /**
     * Handle the subscriber "force deleted" event.
     *
     * @param  \App\Models\Subscribers  $subscriber
     * @return void
     */
    public function forceDeleted(Subscribers $subscriber)
    {
        //
    }
}