<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\CampaignDeleteEvent;
use App\Traits\EmailQueueProcess;

class CampaignDeleteListener implements ShouldQueue
{
    use EmailQueueProcess;
    
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
     * @param  CampaignEvent  $event
     * @return void
     */
    public function handle(CampaignDeleteEvent $event)
    {
        EmailQueueProcess::deleteEmailQueueforCampaign($event->campaign,$event->data);
    }
}