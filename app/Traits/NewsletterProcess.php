<?php

/**
 * Trait for processing NewsletterProcess
 */

namespace App\Traits;


use App\Models\NewsLetter;

use App\Models\User;
use Exception;
use Log;

/**
 * Trait NewsletterProcess
 *
 * Manages newsletter subscription operations including:
 * - Subscribing and unsubscribing users from newsletters
 * - Managing newsletter status and preferences
 * - Logging newsletter activity for audit trails
 *
 * @package App\Traits
 */
trait NewsletterProcess
{
    /**
     * Subscribe or update newsletter subscription status.
     *
     * @param array $data
     * @param int $church_id
     * @param User $user
     * @param User $admin
     * @return array
     */
    public function subscribeNewsletter(array $data, int $church_id, User $user, User $admin): array
    {
        try {
            $user = User::where('name', $user->name)->first();
            $newsletter = NewsLetter::where('email', $user->email)->first();

            if ($newsletter != null) {
                if ($newsletter->status == 0) {
                    $status = 1;
                }
                if ($newsletter->status == 1) {
                    $status = 0;
                }

                $newsletter->status = $status;

                $newsletter->save();
            } else {
                $newsletter = new NewsLetter;

                $newsletter->church_id  = $church_id;
                $newsletter->name       = $user->name;
                $newsletter->email      = $user->email;
                $newsletter->status     = 1;

                $newsletter->save();
            }

            $message = 'NewsLetter Status Updated Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $newsletter,
                $admin,
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_CHANGE_NEWSLETTER_STATUS,
                $message,
            );

            $res['success'] = $message;
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
