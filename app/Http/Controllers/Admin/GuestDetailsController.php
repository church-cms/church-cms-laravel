<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ActivityLog as ActivityLogResource;
use App\Http\Resources\UserDetail as UserDetailResource;
use App\Http\Resources\SendMail as SendMailResource;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\NewsLetter;
use App\Models\SendMail;
use App\Models\User;

/**
 * GuestDetailsController
 *
 * Displays detailed information about guest/visitor accounts.
 * Shows guest profile, activity logs, groups, and communication history.
 * Provides comprehensive guest account overview for administrators.
 *
 * @package App\Http\Controllers\Admin
 */
class GuestDetailsController extends Controller
{
    //

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showDetails($name)
    {
        //
        $users = User::with('userprofile')->where('name', $name)->get();
        $users = UserDetailResource::collection($users);

        return $users;
    }

    public function showActivity($name)
    {
        //
        $user = User::with('userprofile')->where('name', $name)->first();
        if (Gate::allows('member', $user)) {
            $activitylog = ActivityLog::where('subject_id', $user->userprofile->id)->paginate(5);
            $activitylog = ActivityLogResource::collection($activitylog);

            return $activitylog;
        } else {
            abort(403);
        }
    }

    public function showMessages($name)
    {
        //
        $user = User::with('userprofile')->where('name', $name)->first();
        if (Gate::allows('member', $user)) {
            $messages = SendMail::where('user_id', $user->id)->orderBy('executed_at', 'DESC')->paginate(5);

            $messages = SendMailResource::collection($messages);

            return $messages;
        } else {
            abort(403);
        }
    }

    public function show($name)
    {
        //
        $user = User::with('userprofile')->where('name', $name)->first();

        if (Gate::allows('member', $user)) {
            $newsletter = NewsLetter::where('email', $user->email)->first();
            if ($newsletter != null) {
                $status = $newsletter->status;
            } else {
                $status = 0;
            }

            if ($_SERVER['HTTP_REFERER'] != null) {
                $prev_url = $_SERVER['HTTP_REFERER'];
            } else {
                $prev_url = url('/admin/guests');
            }

            return view('/admin/guest/show', ['user' => $user, 'status' => $status, 'prev_url' => $prev_url]);
        } else {
            abort(403);
        }
    }
}
