<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\HelpRepositoryInterface;
use App\Events\Notification\PrayerNotificationEvent;
use App\Http\Resources\Help as HelpResource;
use App\Http\Requests\HelpUpdateRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Events\PushEvent;
use App\Traits\Common;
use Exception;
use Log;

/**
 * HelpsController
 *
 * Manages help requests and support ticket system.
 * Handles help request viewing, responding, and resolution tracking.
 * Supports push notifications for new help requests.
 * Integrates with HelpRepository for data access patterns.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class HelpsController extends Controller
{
    use LogActivity;
    use Common;

    public function __construct(HelpRepositoryInterface $help)
    {
        $this->help = $help;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request, $status)
    {
        //
        $helps = $this->help->getHelps(Auth::user()->church_id)->where('status', $status);

        if (\Request::getQueryString() != null) {
            if ($request->search != null) {
                $helps = $helps->where('title', 'LIKE', '%' . $request->search . '%')->orWhere('description', 'LIKE', '%' . $request->search . '%')->orWhereHas('user', function ($query) use ($request) {
                    $query->whereHas('userprofile', function ($q) use ($request) {
                        $q->where('firstname', 'LIKE', '%' . $request->search . '%')->orWhere('lastname', 'LIKE', '%' . $request->search . '%');
                    });
                });
            }
        }
        $helps = $helps->get();

        $helps = HelpResource::collection($helps);

        return $helps;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $count = $this->help->getHelps(Auth::user()->church_id)->count();

        return view('/admin/helps/index', ['count' => $count]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $help = $this->help->showHelp($id);

        if (Gate::allows('help', $help)) {
            return view('/admin/helps/show', ['help' => $help]);
        } else {
            abort(403);
        }
    }

    public function showDetails($id)
    {
        //
        $array = [];

        $help = $this->help->showHelp($id);

        if (Gate::allows('help', $help)) {
            $array['id']                = $help->id;
            $array['title']             = $help->title;
            $array['description']       = $help->description;
            $array['contact_details']   = $help->contact_details;
            $array['user_name']         = $help->user->name;
            $array['user_fullname']     = $help->user->FullName;
            $array['status']            = $help->status;

            return $array;
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        $help = $this->help->showHelp($id);

        if (Gate::allows('help', $help)) {
            return view('/admin/helps/edit', ['help' => $help]);
        } else {
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HelpUpdateRequest $request, $id)
    {
        //
        try {
            /*$help = Help::where('id',$id)->first();

            $help->status = $request->status;
            if($request->status === 'approve')
            {
                $help->expired_at = Carbon::now()->addDay($request->expired_at)->format('Y-m-d');
                $help->closed_by  = Auth::id();
            }
            else
            {
                $help->comments = $request->comments;
            }

            $help->save();*/

            $help = $this->help->updateHelp($id, $request);

            $data = [];

            $data['church_id'] = Auth::user()->church_id;
            $data['message'] = 'New Help created';
            $data['type'] = 'help';

            event(new PushEvent($data));

            $array = [];

            $array['church_id']         = Auth::user()->church_id;
            $array['user_id']           = $help->user_id;
            $array['details']           = 'New Help Request received';

            event(new PrayerNotificationEvent($array));

            $message = ('Help Approved Successfully');

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $help,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_APPROVED_HELP,
                $message
            );

            $res['message'] = 'Help Status Updated Successfully';

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
