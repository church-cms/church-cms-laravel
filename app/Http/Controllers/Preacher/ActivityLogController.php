<?php

namespace App\Http\Controllers\Preacher;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

/**
 * ActivityLogController
 *
 * Displays activity logs for preacher-specific actions.
 * Shows audit trail of preacher's system interactions and changes.
 * Provides paginated view of activity logs ordered by most recent.
 *
 * @package App\Http\Controllers\Preacher
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
        $activitylog = ActivityLog::with('user')->where('causer_id',Auth::id())->orderby('id','desc')->paginate(10);

        return view('preacher.activity_log.show',['activitylog'=>$activitylog]);
    }
}
