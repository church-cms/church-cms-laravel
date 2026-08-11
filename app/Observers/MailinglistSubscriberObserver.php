<?php

namespace App\Observers;

use App\Events\MailinglistSubscriberEvent;
use App\Models\MailinglistSubscriber;
use App\Traits\EmailQueueProcess;
use App\Events\WebhookSubscriber;
use App\Models\Subscribers;
use App\Models\MailingList;
use Exception;
use Log;

class MailinglistSubscriberObserver
{
     use EmailQueueProcess;
    /**
     * Handle the mailingslist subscriber "created" event.
     *
     * @param  \App\Models\MailinglistSubscriber  $mailinglistSubscriber
     * @return void
     */
    public function created(MailinglistSubscriber $MailinglistSubscriber)
    {
        //
        try
        {
            $data=array();
            event(new MailinglistSubscriberEvent($MailinglistSubscriber,$data));
            $subscriber=Subscribers::where('id',$MailinglistSubscriber->subscriber_id)->first();
            //$mailinglist=MailingList::where('id',$MailinglistSubscriber->mailinglist_id)->with('webhooks')->first();
            $mailinglist=MailingList::where('id',$MailinglistSubscriber->mailinglist_id)->first();
            event(new WebhookSubscriber($mailinglist,$subscriber));  
            //EmailQueueProcess::createEmailQueue($MailinglistSubscriber,$data);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the mailingslist subscriber "updated" event.
     *
     * @param  \App\Models\MailinglistSubscriber  $mailinglistSubscriber
     * @return void
     */
    public function updated(MailinglistSubscriber $mailinglistSubscriber)
    {
        //
    }

    /**
     * Handle the mailingslist subscriber "deleted" event.
     *
     * @param  \App\Models\MailinglistSubscriber  $mailinglistSubscriber
     * @return void
     */
    public function deleted(MailinglistSubscriber $mailinglistSubscriber)
    {
        //
        try
        {
            //$mailingslistSubscriber->subscribers()->detach();//imp

            $subscribers = Subscribers::where('id',$mailinglistSubscriber->subscriber_id)->pluck('subscribers_id')->toArray();
            $subscribers()->detach();//imp
            /*  $data=array();
            EmailQueueProcess::deleteEmailQueueforMailinglistSubscriber($mailingslistSubscriber,$data);*/
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the mailingslist subscriber "restored" event.
     *
     * @param  \App\Models\MailinglistSubscriber  $mailinglistSubscriber
     * @return void
     */
    public function restored(MailinglistSubscriber $mailinglistSubscriber)
    {
        //
    }

    /**
     * Handle the mailingslist subscriber "force deleted" event.
     *
     * @param  \App\Models\MailinglistSubscriber  $mailingslistSubscriber
     * @return void
     */
    public function forceDeleted(MailinglistSubscriber $mailinglistSubscriber)
    {
        //
    }
}