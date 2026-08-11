<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\UserRepositoryInterface;
use App\Http\Requests\ExitMemberRequest;
use App\Events\VerificationMailEvent;
use App\Traits\ResetPasswordProcess;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\MemberProcess;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use App\Traits\LogActivity;
use App\Models\Userprofile;
use App\Models\GroupLink;
use App\Traits\Common;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Exception;
use Log;

/**
 * UserController
 *
 * Manages user and member account operations.
 * Handles user creation, updates, deletion, password resets, and member group management.
 * Supports user verification, group linking, and member process workflows.
 * Integrates with UserRepository for data access patterns.
 *
 * @package App\Http\Controllers\Admin
 * @uses ResetPasswordProcess Trait for password reset functionality
 * @uses MemberProcess Trait for member management workflows
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class UserController extends Controller
{
    use ResetPasswordProcess;
    use MemberProcess;
    use LogActivity;
    use Common;

    public function __construct(UserRepositoryInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function find(Request $request)
    {
        //
        $church_id = Auth::user()->church_id;
        $members = $this->MemberFilter($request, $church_id, 5);
        if (count($members) > 0) {
            return $members;
        }
        return null;
        //
    }

    public function filterList()
    {
        $array = [];

        $array['occupationlist']    = SiteHelper::getOccupationList();
        $array['marriagelist']      = SiteHelper::getMaritalStatusList();
        $array['genderlist']        = SiteHelper::getGenderList();
        $array['months']            = SiteHelper::getMonths();

        return $array;
    }

    public function index()
    {

        $church_id = Auth::user()->church_id;

        // Cached so this doesn't fire on every paginated/filtered request
        $count = Cache::remember("member_count_{$church_id}", 300, function () use ($church_id) {
            return User::ByRole(5)->ByChurch($church_id)->ByStatus('active')->ByMembershipType('member')->count();
        });

        $alphabet = request('alphabet') ? request('alphabet') : '';
        $query    = \Request::getQueryString();

        $type = null;
        if (request('date_of_birth') != null) {
            $type = 'date_of_birth';
        }
        if (request('marriage_status') != null) {
            $type = 'marriage_status';
        }
        if (request('location') != null) {
            $type = 'location';
        }

        // Eager-load userprofile → state/city and usergroup in 3 batch queries (no N+1).
        $usersQuery = User::with(['userprofile.state', 'userprofile.city', 'usergroup'])
            ->ByChurch($church_id)
            ->ByRole(5)
            ->whereHas('userprofile', function ($q) {
                $q->where('membership_type', 'member')->orWhereNull('membership_type');
            });

        // Apply alphabet filter
        if ($alphabet) {
            $usersQuery = $usersQuery->ByFirstName($alphabet);
        }

        // Apply other filters from request
        if (request()->filled('firstname')) {
            $usersQuery = $usersQuery->ByFirstName(request('firstname'));
        }
        if (request()->filled('lastname')) {
            $usersQuery = $usersQuery->ByLastName(request('lastname'));
        }
        if (request()->filled('gender')) {
            $usersQuery = $usersQuery->ByGender(request('gender'));
        }
        if (request()->filled('marriage_status')) {
            $usersQuery = $usersQuery->ByMarriageStatus(request('marriage_status'));
        }
        if (request()->filled('profession')) {
            $usersQuery = $usersQuery->ByProfession(request('profession'));
        }
        if (request()->filled('mobile_no')) {
            $usersQuery = $usersQuery->ByMobile_no(request('mobile_no'));
        }
        if (request()->filled('email')) {
            $usersQuery = $usersQuery->ByEmail_id(request('email'));
        }
        if (request()->filled('location')) {
            $usersQuery = $usersQuery->ByLocation(request('location'));
        }

        // Paginate results (15 per page)
        $usersPaginated = $usersQuery->orderBy('id', 'DESC')->paginate(15);

        // Convert to resource collection
        $membersData = \App\Http\Resources\User::collection($usersPaginated->getCollection());
        $members = json_decode(json_encode($membersData), true);

        return view('/admin/member/index', [
            'alphabet' => $alphabet,
            'query' => $query,
            'count' => $count,
            'type' => $type,
            'members' => $members,
            'paginator' => $usersPaginated
        ]);
    }

    public function updateStatus(Request $request, $name)
    {
        try {

            $user = $this->user->getUser($name)->first();

            $userprofile = Userprofile::where('user_id', $user->id)->first();

            $userprofile->status = $request->status;

            $userprofile->save();

            $message = 'Member Status Updated Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_MEMBER_STATUS,
                $message
            );

            return $message;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function resetPassword($id)
    {
        try {
            $user = User::where('id', $id)->first();
            if (Gate::allows('member', $user)) {
                $this->resetPasswordToUser($user);

                $message = ('Password Reset Mail sent Successfully');

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    $user,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_RESET_PASSWORD,
                    $message
                );
                return redirect()->back();
            } else {
                abort(403);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function emailVerification($id)
    {
        try {
            $user = User::where('id', $id)->first();
            if (Gate::allows('member', $user)) {
                if (env('MAIL_STATUS') === 'on') {
                    event(new VerificationMailEvent($user));
                    \Session::put('successmessage', 'Verification code sent Successfully');
                }

                $message = ('Email Verification code');

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    Auth::user(),
                    $user,
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_EMAIL_VERIFICATION,
                    $message
                );

                \Session::put('failmessage', 'You cannot send message');
                return redirect()->back();
            } else {
                abort(403);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function exitCreate($name)
    {
        //
        $user = User::where('name', $name)->first();

        if (Gate::allows('member', $user)) {
            return view('/admin/member/exit', ['user' => $user]);
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
    public function exitStore(ExitMemberRequest $request, $name)
    {
        //
        try {
            $user = User::where('name', $name)->first();
            $userprofile = Userprofile::where('id', $user->id)->first();

            $userprofile->membership_end_date = array(
                'address'   =>  $request->address,
                'country'   =>  $request->country_id,
                'state'     =>  $request->state_id,
                'city'      =>  $request->city_id,
                'pincode'   =>  $request->pincode,
                'comments'  =>  $request->comments,
                'date'      =>  Carbon::now()->format('Y-m-d H:i:s')
            );
            $userprofile->status = "exit";

            $userprofile->save();

            $groupMembers = GroupLink::where('user_id', $user->id)->get();
            foreach ($groupMembers as $groupMember) {
                $permissions = PermissionUser::where('user_id', $groupMember->user_id)->get();
                foreach ($permissions as $permission) {
                    $permission->delete();
                }
                $groupMember->delete();
            }

            $message = 'Member Exited Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EXIT_MEMBER,
                $message
            );

            //\Session::put('successmessage','Member Exited Successfully');
            //return redirect()->back();

            $res['success'] = $message;

            return $res;
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
    public function destroy($name)
    {
        try {
            $user = User::with('userprofile')->where('name', $name)->first();

            $userprofile = Userprofile::where('user_id', $user->id)->first();
            $userprofile->delete();

            $user->delete();

            $message = 'Member Deleted Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_MEMBER,
                $message
            );
            \Session::put('successmessage', $message);
            return redirect('/admin/members');
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
