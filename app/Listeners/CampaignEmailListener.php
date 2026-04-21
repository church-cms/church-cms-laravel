<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\CampaignEmailEvent;
use App\Traits\EmailQueueProcess;

class CampaignEmailListener implements ShouldQueue
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
     * @param  CampaignEmailEvent  $event
     * @return void
     */
    public function handle(CampaignEmailEvent $event)
    {
        EmailQueueProcess::createEmailQueueforCampaignemail($event->campaignemail,$event->data);
    }
}