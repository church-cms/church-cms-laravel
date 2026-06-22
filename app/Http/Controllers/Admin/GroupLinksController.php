<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Events\SinglePushEvent;
use Illuminate\Http\Request;
use App\Traits\EventProcess;
use App\Traits\LogActivity;
use App\Models\GroupLink;
use App\Traits\Common;
use App\Models\Group;
use App\Models\User;
use Exception;
use Log;
use App\Events\Notification\PushNotificationEvent;

/**
 * GroupLinksController
 *
 * Manages group member associations and permissions.
 * Handles linking users to groups with role-based permissions.
 * Supports group-based access control and member management.
 *
 * @package App\Http\Controllers\Admin
 * @uses EventProcess Trait for event processing
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class GroupLinksController extends Controller
{
    use EventProcess;
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $group = Group::where('id', $id)->first();
        if (Gate::allows('group', $group)) {
            $users = User::where('church_id', Auth::user()->church_id)->ByRole(5)->whereHas('userprofile', function ($q) {
                $q->where('membership_type', 'member')->where('status', 'active');
            })->get();
            return ['memberlist' => UserResource::collection($users)];
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($group_id)
    {
        //
        $group = Group::where('id', $group_id)->first();
        if (Gate::allows('group', $group)) {
            return view('/admin/groups/addMember', ['group' => $group]);
        } else {
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $group_id)
    {
        $churchId  = $request->church_id;
        $role      = $request->role;
        $userIds   = $request->user_ids ?? [];
        $ip        = $this->getRequestIP();
        $added     = 0;
        $skipped   = 0;

        foreach ($userIds as $userId) {
            $exists = GroupLink::where([
                ['church_id', $churchId],
                ['user_id',   $userId],
                ['group_id',  $group_id],
            ])->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            try {
                $grouplink             = new GroupLink;
                $grouplink->church_id  = $churchId;
                $grouplink->user_id    = $userId;
                $grouplink->group_id   = $group_id;
                $grouplink->role       = $role;
                $grouplink->save();

                $user = User::where([['church_id', $churchId], ['id', $userId]])->first();

                event(new SinglePushEvent([
                    'church_id' => Auth::user()->church_id,
                    'user_id'   => $user->id,
                    'message'   => 'You have been added to this group',
                    'type'      => 'group',
                ]));

                $array = [];

                $array['church_id'] = Auth::user()->church_id;
                $array['details'] = 'You have been added to this group';
                $array['message_type'] = 'group';
                $array['message_id'] = $group_id;

                event(new PushNotificationEvent($array));

                $this->doActivityLog(
                    $grouplink,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_ADD_MEMBER_TO_GROUP,
                    'Member Added to Group Successfully'
                );

                $users = User::where('id', $userId)->first();

                $this->doActivityLog(
                    $grouplink,
                    $users,
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_ADD_MEMBER_TO_GROUP,
                    'Member Added to Group Successfully'
                );

                $added++;
            } catch (Exception $e) {
                Log::info($e->getMessage());
            }
        }

        return [
            'success' => "$added member(s) added" . ($skipped ? ", $skipped already in group" : '') . '.',
            'added'   => $added,
            'skipped' => $skipped,
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $member = GroupLink::with('user.userprofile')->where('id', $id)->first();
        if (Gate::allows('group', $member)) {
            return view('/admin/groups/editMember', ['member' => $member]);
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
    public function update(Request $request, $id)
    {
        try {
            $member = GroupLink::where('id', $id)->first();
            $member->role = $request->role;
            $member->save();

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $member,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_UPDATE_MEMBER_PERMISSION,
                'Group member role updated'
            );

            return redirect()->back()->with(['successmessage' => 'Member role updated successfully']);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $member = GroupLink::where('id', $id)->first();
            if (Gate::allows('group', $member)) {
                $member->delete();
                $message = ('Member removed from Group Successfully');

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    $member,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_REMOVE_GROUP_MEMBER,
                    $message
                );

                return redirect()->back()->with(['successmessage' => 'Member removed from Group Successfully']);
            } else {
                abort(403);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
