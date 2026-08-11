<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

/**
 * ActivityLogController
 *
 * Displays and manages user activity audit logs.
 * Shows historical records of admin actions for security and compliance tracking.
 *
 * @package App\Http\Controllers\Admin
 */
class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activitylog = ActivityLog::orderby('id','desc')->with('user')->where('causer_id',Auth::id())->paginate(10);

        return view('admin.activity_log.show',[ 'activitylog'=>$activitylog ]);
    }
}
