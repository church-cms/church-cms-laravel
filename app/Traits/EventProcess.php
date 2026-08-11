<?php

namespace App\Traits;

use App\Events\UserNotifyGroupEvent;
use App\Events\PrayerReminderEvent;
use App\Events\UserReminderEvent;
use App\Events\AttendanceEvent;
use App\Events\ReminderEvent;
use App\Models\Events;
use Exception;
use Log;

/**
 * Trait EventProcess
 *
 * Handles event-related notifications and reminders including:
 * - Sending event reminder notifications via multiple channels
 * - Managing birthday reminder notifications
 * - Sending prayer request reminders
 * - Notifying users in groups about events
 *
 * @package App\Traits
 */
trait EventProcess
{
    /**
     * Send event reminder notifications through multiple channels.
     *
     * @param Events $events
     * @param string $executed_at
     * @param string $check
     * @return void
     */
    public function sendToReminderEvent(Events $events, string $executed_at, string $check): void
    {
        try {
            $options = ['notification', 'sms', 'mail'];

            foreach ($options as $option) {
                $church_id    =  $events->church_id;
                $from         =  env('MAIL_FROM_ADDRESS');
                $subject      =  "Event Remainder Mail";
                $message      =  "Event Notification Mail";
                $entity_id    =  $events->id;
                $entity_name  =  "App\\Models\\Events";
                $via          =  $option;
                $data         =  array('date' => date('Y-m-d', strtotime($events->start_date)), 'location' => $events->location);

                $title        = $events->title;
                $category     = $events->category;
                $date         = $events->start_date;

                if ($check == 'next') {
                    if ($events->repeats == '1') {
                        $freq = $events->freq;
                        if ($events->freq_term == 'week') {
                            $executed_at  =  date('Y-m-d', strtotime('+' . $freq . 'week', strtotime($executed_at)));
                        } elseif ($events->freq_term == 'month') {
                            $executed_at  =  date('Y-m-d', strtotime('+' . $freq . 'month', strtotime($executed_at)));
                        } elseif ($events->freq_term == 'year') {
                            $executed_at  =  date('Y-m-d', strtotime('+' . $freq . 'year', strtotime($executed_at)));
                        } elseif ($events->freq_term == 'day') {
                            $executed_at  =  date('Y-m-d', strtotime('+' . $freq . 'days', strtotime($executed_at)));
                        }
                    } else {
                        $executed_at = $executed_at;
                    }
                }
                if ($executed_at < $events->end_date) {
                    event(new ReminderEvent($church_id, $from, $subject, $message, $entity_id, $entity_name, $via, $data, $executed_at));
                }
            }
            event(new AttendanceEvent($church_id, $entity_id, $entity_name, $title, $category, $date));
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Send birthday reminder notifications to users.
     *
     * @param int $church_id
     * @param int $entity_id
     * @param string|null $date_of_birth
     * @param string|null $marriage_start_date
     * @param array $data
     * @param string $mobile_no
     * @param string $mail
     * @return void
     */
    public function sendToBirthdayReminder(int $church_id, int $entity_id, ?string $date_of_birth, ?string $marriage_start_date, array $data, string $mobile_no, string $mail): void
    {
        try {
            $options = ['notification', 'sms', 'mail'];
            foreach ($options as $option) {
                $from         =  env('MAIL_FROM_ADDRESS');
                if ($date_of_birth != null) {
                    $subject      =  "Birthday Wishes";
                    $message      =  "Birthday Notification Mail";
                    $executed_at  =  date('Y-m-d', strtotime($date_of_birth));
                } else {
                    $subject      =  "Anniversary Wishes";
                    $message      =  "Anniversary Notification Mail";
                    $executed_at  =  date('Y-m-d', strtotime($marriage_start_date));
                }
                $entity_name  =  "App\\Models\\User";
                $via          =  $option;

                /*$array = array('church_id' => $church_id , 'from' => $from , 'mobile_no' => $mobile_no , 'mail' => $mail , 'subject' =>$subject , 'message' =>$message , 'entity_id' =>$entity_id , 'entity_name' =>$entity_name , 'via' =>$via , 'data' =>$data , 'executed_at' => $executed_at );*/
                event(new UserReminderEvent($church_id, $from, $mobile_no, $mail, $subject, $message, $entity_id, $entity_name, $via, $data, $executed_at));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function adminBirthdayReminder($church_id, $user_name, $user_email, $entity_id, $marriage_start_date, $date_of_birth, $mobile_no, $mail, $anniversary_date, $birth_date)
    {
        try {
            $options = ['notification', 'sms', 'mail'];
            foreach ($options as $option) {
                $from         =  env('MAIL_FROM_ADDRESS');
                if ($date_of_birth != null) {
                    $subject      =  "Birthday Remainder Mail";
                    $message      =  "Birthday Notification Mail for <br>
                        Name : " . $user_name . "<br>
                        Email : " . $user_email . "<br>
                        Birth date : " . $date_of_birth;
                    $executed_at  =  date('Y-m-d', strtotime($birth_date));
                } else {
                    $subject      =  "Anniversary Remainder Mail";
                    $message      =  "Anniversary Notification Mail for <br>
                        Name : " . $user_name . "<br>
                        Email : " . $user_email . "<br>
                        Birth date : " . $marriage_start_date;
                    $executed_at  =  date('Y-m-d', strtotime($anniversary_date));
                }
                $entity_name  =  "App\\Models\\User";
                $via          =  $option;
                $data         =  array('subject' => $subject, 'message' => $message);

                event(new UserReminderEvent($church_id, $from, $mobile_no, $mail, $subject, $message, $entity_id, $entity_name, $via, $data, $executed_at));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function sendToPrayerRequestReminder($church_id, $entity_id, $entity_name, $date, $data, $user_id)
    {
        try {
            $options = ['notification', 'sms', 'mail'];
            foreach ($options as $option) {
                $from         =  env('MAIL_FROM_ADDRESS');
                $subject      =  "Prayer Request Remainder Mail";
                $message      =  "Prayer Request Notification Mail";
                $via          =  $option;
                $executed_at  =  date('Y-m-d', strtotime($date));

                event(new PrayerReminderEvent($church_id, $from, $subject, $message, $entity_id, $entity_name, $via, $data, $executed_at, $user_id));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function userNotifyGroup($church_id, $entity_id, $mobile_no, $date)
    {
        try {
            $from         =  env('MAIL_FROM_ADDRESS');
            $subject      =  "Group Notification Mail";
            $message      =  "You have been added to this group and assigned roles.Check with your app to know more<br>
            Click on Dashboard -> Group";
            $entity_name  =  "App\\Models\\GroupLink";
            $via          =  'sms';
            $data         =  array('message' => $message);
            $executed_at  =  date('Y-m-d', strtotime($date));

            event(new UserNotifyGroupEvent($church_id, $from, $mobile_no, $subject, $message, $entity_id, $entity_name, $via, $data, $executed_at));
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
