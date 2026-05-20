<?php

namespace App\Http\Controllers\Api;

use App\Events\Notification\SingleNotificationEvent;
use App\Http\Resources\API\HelpUser as HelpUserResource;
use App\Http\Resources\API\Help as HelpResource;

use App\Http\Requests\HelpAddRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SiteHelper;
use App\Models\Church;
use App\Models\Help;
use Carbon\Carbon;
use Exception;
use Log;
use Illuminate\Http\Request;

/**
 * HelpsController
 *
 * Delivers help/support content via API.
 * Returns approved help articles and support information.
 *
 * @package App\Http\Controllers\Api
 */
class HelpsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $help = Help::where([['church_id',Auth::user()->church_id],['status','approve']])->where('user_id','!=',Auth::id())->latest()->get();
        $help = HelpResource::collection($help);

        return $help;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HelpAddRequest $request)//HelpAdd
    {
        //
        try
        {
            $help = new Help;

            $help->church_id        = Auth::user()->church_id;
            $help->user_id          = Auth::id();
            $help->title            = $request->title;
            $help->description      = $request->description;
            $help->contact_details  = $request->contact_details;
            $help->status           = "pending";

            $help->save();

             $array = [];
             $admin = SiteHelper::getAdmin(Auth::user()->church_id);
             $array['user']     =$admin ;
             $array['details']  = 'New Help Request Received';

             event(new SingleNotificationEvent($array));

            $res['message']='Help Request Added Successfully';
            return $res;
        }

        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
        $help = Help::where([['church_id',Auth::user()->church_id],['user_id',Auth::id()]])->get();
        $help = HelpUserResource::collection($help);

        return $help;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        try
        {
            $help = Help::where('id',$id)->first();

            if(Auth::id() === $help->user_id)
            {
                $help->status           = "close";
                $help->expired_at       = Carbon::now();
                $help->closed_by        = Auth::id();

                $help->save();

                $res['message']='Help Request Closed Successfully';
                return $res;
            }
            else
            {
                return 'Invalid Request';
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
