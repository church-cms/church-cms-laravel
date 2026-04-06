<?php
/**
 * Trait for processing SendMessageProcess
 */
namespace App\Traits;

use App\Events\Notification\SingleNotificationEvent;
use App\Events\SendMessageEvent;
use App\Events\SinglePushEvent;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Traits\SmsProcess;
use App\Models\SendMail;
use App\Traits\Common;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Log;

/**
 * Trait for sending messages and notifications
 *
 * Provides functionality for:
 * - Sending messages via email or SMS
 * - Creating send mail records
 * - Triggering notification events
 * - Broadcasting messages to users
 * - Managing message attachments
 *
 * @package App\Traits
 */
trait SendMessageProcess
{
    use SmsProcess;

    /**
     * Send a message to a user via email or SMS.
     *
     * Creates and sends a message to a user through specified channel (email or SMS).
     * Optionally attaches entity references and triggers notification events.
     *
     * @param object $data The message data object containing mode, subject, message, entity_id, entity_name, attachments
     * @param int $church_id The church ID associated with the message
     * @param string $admin_email The sender's admin email address
     * @param \App\Models\User $user The recipient user
     * @param object $admin The admin/sender user object
     *
     * @return void
     */
    public function sendMessage(object $data, int $church_id, string $admin_email, User $user, object $admin): array|null {
        try {
            $sendmail = new SendMail;

            $sendmail->church_id     = $church_id;
            $sendmail->user_id       = $user->id;
            $sendmail->mode          = $data->mode;
            $sendmail->from          = $admin_email;
            if($data->entity_id != null)
            {
                $sendmail->entity_id = $data->entity_id;
            }
            if($data->entity_name != null)
            {
                $sendmail->entity_name = $data->entity_name;
            }
            if($data->mode == 'mail')
            {
                $sendmail->to       = $user->email;
            }
            else
            {
                $sendmail->to        = $user->mobile_no;
            }
            $sendmail->subject       = $data->subject;
            $sendmail->message       = $data->message;

            if($data->attachments != null)
            {
               // $file = $data->file('attachments');
                 $file = $data->attachments;
                if($file != null)
                {
                    $folder = $church_id.'/sendmessage';
                    $sendmail->attachments  = $this->uploadFile($folder,$file);
                }
            }

            if( $data->executed_at == null )
            {
                $sendmail->executed_at  = Carbon::now();
                $sendmail->fired_at     = Carbon::now();
                $sendmail->is_executed  = 1;
                $sendmail->status       = "delivered";
            }
            else
            {
                $sendmail->executed_at  = date('Y-m-d H:i:s',strtotime($data->executed_at));
                $sendmail->status       = "queue";
            }

            $sendmail->save();

            if( $sendmail->is_executed == 1 )
            {
                if($sendmail->mode == 'mail')
                {
                    event(new SendMessageEvent($sendmail));
                }
                elseif($sendmail->mode == 'notification')
                {
                    $data=[];

                    $data['school_id']  =  $church_id;
                    $data['message']    = 'New Message Received';
                    $data['type']       = 'private message';

                    event(new SinglePushEvent($data));

                     $array = [];

                     $array['user']     = $user;
                     $array['details']  = 'New Message Received';

                     event(new SingleNotificationEvent($array));
                }
                elseif($sendmail->mode == 'sms')
                {
                    $this->sendPrivateMessage($sendmail->to,$sendmail->message);
                }
            }

            $message= 'Message Sent Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $sendmail,
                $admin,
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_SEND_MESSAGE,
                $message
            );

            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
}
