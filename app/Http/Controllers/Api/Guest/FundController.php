<?php

namespace App\Http\Controllers\Api\Guest;

use App\Events\Notification\SingleNotificationEvent;
use App\Http\Requests\GuestFundRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SiteHelper;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\Fund;
use Exception;
use Log;

class FundController extends Controller
{
    use LogActivity;
    use Common;

    public function store(GuestFundRequest $request)
    {
        try {
            $fund = new Fund;

            $fund->church_id        = $request->church_id;

            $array = [];

            $fund->membership       = 'guest';

            $array['first_name']    = $request->first_name;
            $array['last_name']     = $request->last_name;
            $array['address']       = $request->address;
            $array['mobile_number'] = $request->mobile_number;

            $fund->data             = $array;
            $fund->amount           = $request->amount;
            $fund->payaccount_id    = $request->payaccount_id;
            $fund->status           = 'request';
            $fund->uuid             = uniqid();

            $fund->save();

            $message = 'Fund Requested Successfully';

            $array = [];
            $admin = SiteHelper::getAdmin($fund->church_id);
            $array['user']     = $admin;
            $array['details']  = 'New Fund Request Received';

            event(new SingleNotificationEvent($array));

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $fund,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_FUND,
                $message
            );

            $res['success'] = $message;

            return response()->json([
                'status'    =>  true,
                'message'   =>  $message,
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
