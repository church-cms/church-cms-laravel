<?php

namespace App\Http\Controllers\Api;

use App\Events\Notification\SingleNotificationEvent;
use App\Http\Resources\API\Fund as FundResource;
use App\Http\Requests\Api\FundRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Traits\Common;
use App\Models\Church;
use App\Models\Fund;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Log;
use OpenApi\Attributes as OA;

/**
 * FundController
 *
 * Provides donation and fundraising information via API.
 * Returns fund listings, donations, and payment methods.
 *
 * @package App\Http\Controllers\Api
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class FundController extends Controller
{
    use LogActivity;
    use Common;

    #[OA\Get(
        path: '/api/v1/myFunds',
        summary: 'Get current user\'s fund requests',
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/FundResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function myFunds()
    {
        $funds = Fund::where([['user_id',Auth::id()],['church_id',Auth::user()->church_id]])->get();

        $funds = FundResource::collection($funds);

        return $funds;
    }

    #[OA\Get(
        path: '/api/v1/fund/list',
        summary: 'List all deposited funds from other members',
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/FundResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function list()
    {
        $funds = Fund::where([['user_id','!=',Auth::id()],['church_id',Auth::user()->church_id],['status','deposited']])->get();

        $funds = FundResource::collection($funds);

        return $funds;
    }

    #[OA\Post(
        path: '/api/v1/add/fund',
        summary: 'Submit a new fund request',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/AddFundRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/AddFundResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function store(FundRequest $request)
    {
    	$user=User::where([['id',Auth::id()],['church_id',Auth::user()->church_id]])->first();
        try
        {
            $funds= new Fund();

            $funds->church_id         = Auth::user()->church_id;
           /* $funds->authorised_by     = Auth::id();
            $funds->authorised_at     = Carbon::now();*/

            $funds->membership        ='member';

            $funds->user_id           = $user->id;

            $funds->amount            = $request->amount;

            $funds->payaccount_id     = $request->payaccount_id;

            $funds->status            = 'request';

            $funds->uuid=uniqid();

            $funds->save();

            $message= 'Fund Requested Successfully';

             $array = [];
             $admin = SiteHelper::getAdmin(Auth::user()->church_id);
             $array['user']     =$admin ;
             $array['details']  = 'New Fund Request Received';

             event(new SingleNotificationEvent($array));

            /*$ip= $this->getRequestIP();
            $this->doActivityLog(
                $fund,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_FUND,
                $message
            ); */

            $res['success']=$message;
              return response()->json([
                'status'    =>  true,
                'message'   =>  $message,
            ], 200);
        }
        catch(Exception $e)
        {
              return response()->json([
                'status'    =>  false,
                'message'   =>  'Something went wrong',
            ], 200);
            Log::info($e->getMessage());

        }
    }
}
