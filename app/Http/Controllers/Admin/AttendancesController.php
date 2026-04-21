<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ImportMemberRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Imports\SummaryImport;
use App\Imports\AttendanceImport;
use App\Traits\LogActivity;
use App\Models\Events;
use App\Traits\Common;
use League\Csv\Writer;
use Exception;
use Excel;
use Log;

/**
 * AttendancesController
 *
 * Handles event attendance tracking and member check-ins.
 * Manages attendance records, summary reports, and bulk attendance imports.
 * Supports CSV export and attendance management for church events.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for attendance logging
 * @uses Common Trait for utility functions
 */
class AttendancesController extends Controller
{
    use LogActivity;
    use Common;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        $csv->insertOne(['user_mobile','event_title','event_category','date']);

        $csv->insertOne(['(mobile_number)','(title)','(prayer,education,meeting,culturals,sermon)','(event_date)','Delete this entire row before importing']);

        $csv->output('Church Social Event Summary Format'.date('_d-m-Y_H:i').'.csv');

        $message=('Downloaded Sample Format File Successfully');

        $ip= $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
            LOGNAME_DOWNLOAD_SAMPLE_FORMAT,
            $message
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("/admin/attendances/attendance");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImportMemberRequest $request)
    {
        //
        try
        {
            $file = Excel::import(new SummaryImport,$request->file('import_file'));
            $insertedcount = \Session::get('insertedcount');
            if($insertedcount > 0)
            {
                $message=('Imported Events Summary Successfully');

                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    Auth::user(),
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_IMPORT_EVENT_SUMMARY,
                    $message
                );

                return back()->with('successmessage',$insertedcount.' Records Imported Successfully');
            }
            else
            {
                return back()->with('failmessage','Data Already Exists.No Records Imported');
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function summary($event_id)
    {
        //
        $event=Events::where([['church_id',Auth::user()->church_id],['id',$event_id]])->first();

        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        $csv->insertOne(['user_mobile']);

        $csv->insertOne(['(mobile_number)','Delete this entire row before importing']);

        $csv->output('Church Social '.$event->title.' Summary Format'.date('_d-m-Y_H:i').'.csv');

        $message=('Downloaded Sample Format File Successfully');

        $ip= $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
            LOGNAME_DOWNLOAD_SAMPLE_FORMAT,
            $message
        );
    }

    public function createAttendance($event_id)
    {
        //
        $event=Events::where([['church_id',Auth::user()->church_id],['id',$event_id]])->first();
        return view("/admin/attendances/event/attendance",['event'=>$event]);
    }

    public function saveAttendance(ImportMemberRequest $request,$event_id)
    {

        //
        try
        {
            $file = Excel::import(new AttendanceImport($event_id),$request->file('import_file'));
            $insertedcount = \Session::get('insertedcount');
            if($insertedcount > 0)
            {
                $message=('Imported Events Summary Successfully');

                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    Auth::user(),
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_IMPORT_EVENT_SUMMARY,
                    $message
                );
                \Session::forget('insertedcount');
                return back()->with('successmessage',$insertedcount.' Records Imported Successfully');
            }
            else
            {
                return back()->with('failmessage','Data Already Exists.No Records Imported');
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
}
