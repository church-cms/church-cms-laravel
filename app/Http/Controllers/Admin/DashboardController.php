<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ShowSermonLink as ShowSermonLinkResource;
use App\Http\Resources\ShowEvent as ShowEventResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\SermonLink;
use App\Models\Attendance;
use App\Traits\Dashboard;
use App\Models\Events;


/**
 * DashboardController
 *
 * Manages the admin dashboard interface displaying key metrics and summaries.
 * Provides analytics overview, upcoming events,  recent sermon links, and attendance tracking.
 * Integrates with Spatie Analytics for tracking website visitor data.
 *
 * @package App\Http\Controllers\Admin
 * @uses Dashboard Trait for common dashboard calculation methods
 */
class DashboardController extends Controller
{
    use Dashboard;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin_id  = Auth::id();
        $church_id = Auth::user()->church_id;

        $dashboard = $this->adminDashboard($church_id, $admin_id);

        return view('/admin/dashboard/dashboard', ['church_id' => $church_id, 'dashboard' => $dashboard]);
    }

    public function event()
    {
        $event = Events::where('church_id',Auth::user()->church_id)->where('start_date','>=',date('Y-m-d H:i:s'))->orderBy('start_date','asc')->take(4)->get(); //for demo changed to 3

        $event = ShowEventResource::collection($event);

        return $event;
    }

    public function sermon()
    {
        $links = SermonLink::with('sermons')->where('church_id',Auth::user()->church_id)->orderBy('id','desc')->take(5)->get();

        $links = ShowSermonLinkResource::collection($links);

        return $links;
    }

    public function absent()
    {
        $absents = Attendance::where([['church_id',Auth::user()->church_id],['is_present',0]])->paginate(20);

        return view('/admin/dashboard/absent' , ['absents' => $absents]);
    }
}
