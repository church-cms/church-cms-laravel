<?php

namespace App\Http\Controllers\Preacher;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Userprofile;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\User;
use Exception;
use Hash;
use Log;

/**
 * PreacherController
 *
 * Manages preacher account information and profile settings.
 * Handles password changes and user profile updates for preachers.
 * Uses LogActivity and Common traits for auditing and utilities.
 *
 * @package App\Http\Controllers\Preacher
 */
class PreacherController extends Controller
{
    use LogActivity;
    use Common;

    public function ChangePassword()
    {
        return view('/preacher/changepassword');
    }

    public function changeavatar(Request $request)
    {
        return view('preacher/changeavatar');
    }

    public function getavatar()
    {
        $userprofile = Userprofile::where('user_id', Auth::id())->first();
        $array = [];

        if (Auth::user()) {
            $array['avatar'] = $this->getFilePath($userprofile->avatar);
            $array['id'] = $userprofile->id;
        }

        return $array;
    }

    /**
     * Updates the avatar image for specified user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function updatechangeavatar(Request $request)
    {
        try {
            $user = User::where('id', Auth::id())->first();
            $userprofile = Userprofile::where('user_id', Auth::id())->first();
            $path = $this->uploadFile('uploads/avatars', $request->avatar);
            if ($path != '') {
                $userprofile->avatar = $path;
                $userprofile->save();
                \Session::put('successmessage', __('admin_userprofile.update_avatar'));
                $res['message'] = __('admin_userprofile.update_avatar');
                $res['avatar'] = $this->getFilePath($path);
            } else {
                $userprofile->avatar = 'uploads/user/avatar/default-user.jpg';
                $userprofile->save();
                \Session::put('failmessage', __('admin_userprofile.failed_avatar'));
                $res['message'] = __('admin_userprofile.failed_avatar');
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_CHANGE_AVATAR,
                'Changed Avatar'
            );
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Updates the password of the specified user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function updateChangePassword(ChangePasswordRequest $request)

    {
        $user = User::find(Auth::id());
        $hashedPassword = $user->password;

        if ($hashedPassword != '') {
            $user->password = Hash::make($request->newpassword);
            $user->save();

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_CHANGE_PASSWORD,
                'Changed Profile Password.'
            );

            $successmessage = $request->session()->flash('successmessage', __('admin_userprofile.password_update'));
        }

        $res['message'] = __('admin_userprofile.password_update');
        return $res;
    }

    public function edit()
    {
        $user = User::where('id', Auth::id())->first();

        return view('/preacher/editprofile', ['user' => $user]);
    }

    public function create()
    {
        $userprofile = Userprofile::where('user_id', Auth::id())->first();

        $array = [];

        $array['firstname']         = $userprofile->firstname;
        $array['lastname']          = $userprofile->lastname;
        $array['avatar_display']    = $userprofile->AvatarPath;
        $array['description']       = $userprofile->description === null ? null : $userprofile->description;
        $array['facebook_id']       = $userprofile->facebook_id === null ? null : $userprofile->facebook_id;

        return $array;
    }

    public function update(Request $request)
    {
        try {
            $userprofile = Userprofile::where('user_id', Auth::id())->first();

            $userprofile->firstname = $request->firstname;
            $userprofile->facebook_id = $request->facebook_id;

            $userprofile->save();

            $message = __('admin_userprofile.profile_update');

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_USERPROFILE,
                $message
            );

            $res['success'] = $message;
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
