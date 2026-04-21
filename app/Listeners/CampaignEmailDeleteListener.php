<?php

namespace App\Listeners;

use App\Events\CampaignEmailDeleteEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Traits\EmailQueueProcess;

class CampaignEmailDeleteListener implements ShouldQueue
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
     * @param  CampaignEmailDeleteEvent  $event
     * @return void
     */
    public function handle(CampaignEmailDeleteEvent $event)
    {
        EmailQueueProcess::deattchEmailQueueforCampaignemail($event->campaignemail,$event->data);
    }
}
