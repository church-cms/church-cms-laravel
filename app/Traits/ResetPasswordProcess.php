<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
use App\Traits\SmsProcess;
use Carbon\Carbon;
use Exception;
use Hash;
use Log;
use DB;

/**
 * Trait ResetPasswordProcess
 *
 * Handles password reset functionality including:
 * - Generating reset tokens for users
 * - Creating password reset entries in database
 * - Sending reset password emails and SMS notifications
 * - Managing reset token creation and storage
 *
 * @package App\Traits
 */
trait ResetPasswordProcess
{
    use SmsProcess;

    /**
     * Send password reset email and SMS to user.
     *
     * @param User $model
     * @return void
     */
    public function resetPasswordToUser(User $model): void
    {
        try
        {
            $token = \Str::random(10);
            $password = \DB::table(config('auth.passwords.users.table'))->insert([
                'email'         => $model->email,
                'token'         => Hash::make($token),
                'created_at'    => Carbon::now()
            ]);
            if($password)
            {
                if( (env('MAIL_STATUS') == 'on') && ($model->email != '') )
                {
                    Mail::to($model->email)->queue(new ResetPassword($model,$token));

                    \Session::put('successmessage','Check your email to reset the password');
                }
                else
                {
                    \Session::put('failmessage','You cannot send message');
                }
            }
            else
            {
                \Session::put('successmessage','Password Reset failed for this User');
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Send password reset SMS to user.
     *
     * @param User $user
     * @return void
     */
    public function resetPasswordSms(User $user): void
    {
        try
        {
            $token = \Str::random(10);
            $password = \DB::table(config('auth.passwords.users.table'))->insert([
                'email'         => $user->email,
                'token'         => Hash::make($token),
                'created_at'    => Carbon::now()
            ]);

            if( ($password) && (env('SMS_STATUS') == 'on') )
            {
                $url = url('/password/reset/'.$token);
                $this->sendUserResetPassword($user->mobile_no,$url);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
}
