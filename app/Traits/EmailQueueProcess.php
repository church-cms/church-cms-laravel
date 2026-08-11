<?php
/**
 * Trait EmailQueueProcess
 *
 * Manages email queue creation and processing for campaigns including:
 * - Creating email queues from mailing list subscribers
 * - Processing campaign emails with content replacement
 * - Scheduling email delivery with delays and personalization
 * - Handling unsubscribe links and content variables
 *
 * @package App\Traits
 */
namespace App\Traits;

use App\Models\MailinglistSubscriber;
use App\Models\CampaignEmail;
use App\Models\MailingList;
use App\Models\Subscribers;
use App\Models\MailQueue;
use App\Models\Campaign;
use App\Models\Email;
use Carbon\Carbon;
use Exception;
use Log;

trait EmailQueueProcess
{
    /**
     * Create email queue for a mailing list subscriber.
     *
     * @param MailinglistSubscriber $mailinglistSubscriber
     * @param object $request
     * @return array|mixed
     */
    public static function createEmailQueue(MailinglistSubscriber $mailinglistSubscriber, object $request)
    {
        $mailqueue=[];
        try
        {
            if($mailinglistSubscriber->status==1)
            {
                $mailqueue = EmailQueueProcess::makeEmailQueue($mailinglistSubscriber->subscribers_id,$mailinglistSubscriber->mailing_list_id);

                return $mailqueue;
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Generate email queue entries for a subscriber and mailing list.
     *
     * @param int $subscriber_id
     * @param int $mailinglist_id
     * @return MailQueue|array
     */
    public static function makeEmailQueue(int $subscriber_id, int $mailinglist_id)
    {
        $mailqueue=[];
        try
        {
            $data = [];

            $data['subscriber_id']  =   $subscriber_id;
            $data['mailinglist_id'] =   $mailinglist_id;
            $data['campaign_id']    =   $campaign->id;
            $data['to_mail']        =   $subscriber->email;

            $campaign   =   Campaign::where('mailinglist_id',$mailinglist_id)->first();//reln
            $subscriber =   Subscribers::where('id',$subscriber_id)->first();
            $emails     =   CampaignEmail::where('campaign_id',$campaign->id)->get();//reln

            if(count($emails)>0)
            {
                foreach($emails as $campaignemail)
                {
                    $data['email_id']       =   $campaignemail->email_id;
                    $data['subject']        =   optional($campaignemail->email)->subject;
                    $data['from_email']     =   optional($campaignemail->email)->from_email;
                    $data['from_name']      =   optional($campaignemail->email)->from_name;
                    $data['reply_to_email'] =   optional($campaignemail->email)->reply_to_email;
                    //Replace
                    $content =  optional($campaignemail->email)->content;
                    $content = str_replace(':firstname', $subscriber->firstname, $content);
                    $content = str_replace(':lastname', $subscriber->lastname, $content);
                    $url = url('/unsubscribe/'.$campaign->mailinglist->slug.'?email='.$subscriber->email);

                    $content = str_replace(':unsubscribelink', $url, $content);
                    $data['content'] = $content;
                    $data['scheduled_at'] = $campaignemail->ScheduleAt();

                    $mailqueue = MailQueue::create($data);
                }
            }
            return $mailqueue;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Create email queue from campaign email.
     *
     * @param CampaignEmail $campaignemail
     * @param object $request
     * @return array
     */
    public static function createEmailQueueforCampaignemail(CampaignEmail $campaignemail, object $request)
    {
        $mailqueue=[];
        try
        {
            $data=array();

            $data['email_id']=$campaignemail->email_id;
            //  $data['scheduled_at']=Carbon::now()->addHours($campaignemail->delay_in_hours);
            $data['scheduled_at']=$campaignemail->ScheduleAt();
            $data['campaign_id']=$campaignemail->campaign_id;
            $campaign=Campaign::where('id',$campaignemail->campaign_id)->first();
            $email=Email::where('id',$campaignemail->email_id)->first();

            $data['subject']=$email->subject;
            $data['from_email']=$email->from_email;
            $data['from_name']=$email->from_name;
            $data['reply_to_email']=$email->reply_to_email;
            // $data['content']=$email->content;
            $data['mailinglist_id']=$campaign->mailinglist_id;
            $mailinglist=MailinglistSubscriber::where('mailinglist_id',$campaign->mailinglist_id)->where('status','1')->get();

            if(count($mailinglist)>0)
            {
                foreach($mailinglist as $subscriber)
                {
                    //Replace
                    $content=$email->content;
                    $content= str_replace(':firstname',$subscriber->subscriber->firstname,$content);
                    $content= str_replace(':lastname',$subscriber->subscriber->lastname,$content);
                    $url=url('/unsubscribe/'.$campaign->mailinglist->slug.'?email='.$subscriber->email);
                    $content=str_replace(':unsubscribelink',$url,$content);

                    $data['content']=$content;

                    $data['subscriber_id']=$subscriber->subscriber->id;
                    $data['to_mail']=$subscriber->subscriber->email;
                    $mailqueue = MailQueue::create($data);
                }
            }
            return $mailqueue;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    public static function deattchEmailQueueforCampaignemail($campaignemail,$request)
    {
        try
        {
            $mailqueue=[];

            $mailqueue = MailQueue::where([['email_id',$campaignemail->email_id],['campaign_id',$campaignemail->campaign_id]])->whereNull('deleted_at')->delete();

            return $mailqueue;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    public static function deleteEmailQueueforCampaign($campaign,$request)
    {
        try
        {
            $mailqueue=[];

            $mailqueue = MailQueue::where('campaign_id',$campaign->id)->whereNull('deleted_at')->delete();

            return $mailqueue;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    public static function deleteEmailQueueforSubscribers($subscriber,$request)
    {
        try
        {
            $mailqueue=[];

            $mailqueue = MailQueue::where('subscriber_id',$subscriber->id)->whereNull('deleted_at')->delete();

            return $mailqueue;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    public static function  deleteEmailQueueforMailinglistSubscriber($mailingslistSubscriber,$request)
    {
        try
        {
            $mailqueue=[];

            $mailqueue = MailQueue::where('mailinglist_id',$mailingslistSubscriber->mailinglist_id)->whereNull('deleted_at')->delete();

            return $mailqueue;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    public static function createEmailQueueafterConfirm($mailinglist_id,$subscriber_id)
    {
        $mailqueue=[];
        try
        {
            EmailQueueProcess::makeEmailQueue($subscriber_id,$mailinglist_id);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            return $mailqueue;
        }
    }
}
