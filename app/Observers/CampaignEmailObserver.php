<?php

namespace App\Observers;

use App\Events\CampaignEmailDeleteEvent;
use App\Events\CampaignEmailEvent;
use App\Traits\EmailQueueProcess;
use App\Models\CampaignEmail;
use Exception;
use Log;

class CampaignEmailObserver
{
    use EmailQueueProcess;

    /**
     * Handle the campaignemail "created" event.
     *
     * @param  \App\Models\CampaignEmail  $campaignemail
     * @return void
     */
    public function created(CampaignEmail $campaignemail)
    {
        //
        try
        {
            $data=array();
            event(new CampaignEmailEvent($campaignemail,$data));
            //EmailQueueProcess::createEmailQueueforCampaignemail($campaignemail,$data);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the campaignemail "updated" event.
     *
     * @param  \App\Models\CampaignEmail  $campaignemail
     * @return void
     */
    public function updated(CampaignEmail $campaignemail)
    {
        //
    }

    /**
     * Handle the campaignemail "deleted" event.
     *
     * @param  \App\Models\CampaignEmail  $campaignemail
     * @return void
     */
    public function deleted(CampaignEmail $campaignemail)
    {
        //
        try
        {
            $data=array();
            event(new CampaignEmailDeleteEvent($campaignemail,$data));
            //EmailQueueProcess::deattchEmailQueueforCampaignemail($campaignemail,$data);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the campaignemail "restored" event.
     *
     * @param  \App\Models\CampaignEmail  $campaignemail
     * @return void
     */
    public function restored(CampaignEmail $campaignemail)
    {
        //
    }

    /**
     * Handle the campaignemail "force deleted" event.
     *
     * @param  \App\Models\CampaignEmail  $campaignemail
     * @return void
     */
    public function forceDeleted(CampaignEmail $campaignemail)
    {
        //
    }
}