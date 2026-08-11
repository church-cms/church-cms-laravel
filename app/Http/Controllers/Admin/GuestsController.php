<?php

namespace App\Http\Controllers\Admin;

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
use Carbon\Carbon;
use Exception;
use Log;

/**
 * GuestsController
 *
 * Manages guest/visitor listing and search within the admin panel.
 * Displays all guests with filtering, searching, and pagination functionality.
 * Integrates with member processes and password reset capabilities.
 *
 * @package App\Http\Controllers\Admin
 * @uses ResetPasswordProcess Trait for password reset functionality
 * @uses MemberProcess Trait for member processing
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class GuestsController extends Controller
{
    use ResetPasswordProcess;
    use MemberProcess;
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function find(Request $request)
    {
        //
        $church_id = Auth::user()->church_id;

        $guests = $this->GuestFilter($request, $church_id, 5);
        if (count($guests) > 0) {
            return $guests;
        }
        return null;
    }

    public function filterList()
    {
        $array = [];

        $array['occupationlist']    = SiteHelper::getOccupationList();
        $array['genderlist']        = SiteHelper::getGenderList();
        $array['months']            = SiteHelper::getMonths();

        return $array;
    }

    public function index(Request $request)
    {
        $alphabet   = $request->input('alphabet', '');
        $firstname  = $request->input('firstname', '');
        $lastname   = $request->input('lastname', '');
        $gender     = $request->input('gender', '');
        $minAge     = $request->input('min_age', '');
        $maxAge     = $request->input('max_age', '');
        $profession = $request->input('profession', '');
        $mobile     = $request->input('mobile_no', '');
        $email      = $request->input('email', '');
        $location   = $request->input('location', '');
        $dob        = $request->input('date_of_birth', '');

        $query = User::ByRole(5)
                     ->ByChurch(Auth::user()->church_id)
                     ->ByMembershipType('guest')
                     ->whereHas('userprofile', fn($q) => $q->whereIn('status', ['active', 'inactive']));

        if ($alphabet)   $query->ByFirstName($alphabet);
        if ($firstname)  $query->ByFirstName($firstname);
        if ($lastname)   $query->ByLastName($lastname);
        if ($gender)     $query->ByGender($gender);
        if ($minAge || $maxAge) {
            $year = (int) date('Y');
            $query->ByAge(
                $minAge ? $year - (int)$minAge : $year,
                $maxAge ? $year - (int)$maxAge : 1900
            );
        }
        if ($dob)        $query->ByDateOfBirth($dob);
        if ($profession) $query->ByProfession($profession);
        if ($mobile)     $query->ByMobile_no($mobile);
        if ($email)      $query->ByEmail_id($email);
        if ($location)   $query->ByLocation($location);

        $guests = $query->with('userprofile')
                        ->orderBy('created_at', 'desc')
                        ->paginate(24)
                        ->withQueryString();

        $count = $guests->total();

        $occupations = Userprofile::where('church_id', Auth::user()->church_id)
                        ->whereNotNull('profession')
                        ->where('profession', '!=', '')
                        ->distinct()
                        ->pluck('profession')
                        ->sort()
                        ->values();

        return view('admin.guest.index', compact(
            'guests', 'count', 'alphabet',
            'firstname', 'lastname', 'gender',
            'minAge', 'maxAge', 'dob', 'profession',
            'mobile', 'email', 'location',
            'occupations'
        ));
    }

    public function updateStatus(Request $request, $name)
    {
        try {
            $user = User::where('name', $name)->first();
            $userprofile = Userprofile::where('id', $user->id)->first();

            $userprofile->status = $request->status;

            $userprofile->save();

            $message = 'Guest Status Updated Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_MEMBER_STATUS,
                $message
            );
            \Session::put('successmessage', 'Guest Status Updated Successfully');
            return redirect()->back();
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
            return view('/admin/guest/exit', ['user' => $user]);
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

            $message = 'Guest Exited Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EXIT_MEMBER,
                $message
            );

            //\Session::put('successmessage','Member Exited Successfully');


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

            $message = 'Guest Deleted Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_MEMBER,
                $message
            );
            \Session::put('successmessage', $message);
            return redirect('/admin/guests');
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
