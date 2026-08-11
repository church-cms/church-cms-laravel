<?php

namespace App\Traits;

use App\Models\Authentication;
use App\Traits\MSG91;
use Carbon\Carbon;
use Exception;
use Log;

/**
 * Trait AuthenticationProcess
 *
 * Manages user authentication records and token creation including:
 * - Creating authentication tokens and OTP verification
 * - Managing authentication status and expiry
 * - Recording IP addresses and authentication attempts
 *
 * @package App\Traits
 */
trait AuthenticationProcess
{
    use MSG91;

    /* public function createAuthentication($user,$request)
    {
        try
        {
            if( $user->mobile_no !=null )
            {
                $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                $otp = '';
                for ($i = 0; $i < 10; $i++) {
                    $otp .= $characters[mt_rand(0, 61)];
                }
                $expiry = Carbon::now()->addMinutes(5);

                $authentication             =   new Authentication;

                $authentication->user_id    =   $user->id;
                $authentication->token      =   $otp;
                $authentication->status     =   0;
                $authentication->expires_on =   $expiry;
                if($request!='')
                {
                    $authentication->ip_address=$request->ip();
                }
                else
                {
                    $authentication->ip_address=\Request::ip();
                }
                $authentication->save();

                $template = Smstemplate::where([['name','reset_password'],['status','1']])->first();
                $content  = $template->content;

                $content = str_replace(":token",$otp,$content);
                $sms = env('SMS_GATEWAY');

                if($sms)
                {
                    $this->sendPrivateSMS($content,$user->mobile_no);
                }

                \Session::put('successmessage',trans('messages.otp_success_msg'));
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }*/

    public function createAuthentication($user, $request)
    {
        try {
            if ($user->mobile_no != null) {
                $otp = rand(100000, 999999);
                $expiry = Carbon::now()->addMinutes(5);
                //$expiry = Carbon::now()->addDay(1); //for test

                $authentication             =   new Authentication;

                $authentication->user_id    =   $user->id;
                //$authentication->type       =   $type;
                $authentication->token      =   $otp;
                $authentication->status     =   0;
                $authentication->expires_on =   $expiry;
                if ($request != '') {
                    $authentication->ip_address = $request->ip();
                } else {
                    $authentication->ip_address = \Request::ip();
                }
                $authentication->save();

                $this->getOTP($otp, $user->mobile_no);

                \Session::put('successmessage', trans('messages.otp_success_msg'));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
