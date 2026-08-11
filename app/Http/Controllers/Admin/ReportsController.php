<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ImportMemberRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Imports\SummaryImport;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Models\Userprofile;
use App\Models\Reminder;
use App\Models\Gallery;
use App\Traits\Common;
use App\Models\Events;
use League\Csv\Writer;
use App\Models\User;
use App\Models\File;
use App\Models\Plan;
use SplFileObject;
use Exception;
use Excel;

/**
 * ReportsController
 *
 * Handles generation and retrieval of various church reports and analytics.
 * Manages member statistics, message history, subscription reports, and data exports.
 * Provides reporting functionality for church administrators to track metrics and trends.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for activity tracking
 * @uses Common Trait for utility functions
 */
class ReportsController extends Controller
{
    use LogActivity;
    use Common;

    public function report()
    {
        //
        return view("/admin/reports/reports");
    }

    public function messageHistory($subject)
    {
        //
        $reports = Reminder::where('church_id',Auth::user()->church_id)->where('to','!=',env('MAIL_FROM_ADDRESS'))->where('subject',$subject)->paginate(20);

        return view("/admin/reports/messages",['reports'=>$reports , 'subject' => $subject]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $subscription = Subscription::where('church_id',Auth::user()->church_id)->get();
        // foreach($subscriptions as $subscription)
        // {
        //     $plan = Plan::where('id',$subscription->plan_id)->get();
        // }

        // return view("/admin/reports/index",['subscriptions'=>$subscription , 'plan'=>$plan]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $start  = date("Y-m-d",strtotime($request->from_date));
        $end    = date("Y-m-d",strtotime($request->to_date));


       $subscription=array();
        return view('/admin/reports/filter',['subscriptions'=>$subscription]);
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
        $subscription=array();
        if(Gate::allows('subscription',$subscription))
        {
            return view("/admin/reports/show",['subscriptions'=>$subscription]);
        }
        else
        {
            abort(403);
        }
    }

    public function exportBirthday()
    {
        /*$users = User::with('userprofile')->where('church_id',Auth::user()->church_id)->ByRole(5)->get();
        $response = Response::make($users, 200);

        $response->header('Content-Type', 'application/pdf');

        return $response;*/

        /*$path = 'uploads/export/export'.date('_d-m-Y_H_i_s').'.csv';
        Excel::store(new UsersExport, $path);
        $path_send = '<a href="{{ url($path) }}">';
        \Session::put('path', url($path));

        return back();*/
        /*try
        {
            $users = User::with('userprofile')->where('church_id',Auth::user()->church_id)->ByRole(5)->get();

            $file= \Storage::url('uploads/export/export'.date('_d-m-Y_H_i_s').'.csv');
            $file_open = fopen($file, 'w');
            $ex = fputcsv($file_open, $users);
            $successpath=$file;
        }
        catch(Exception $e)
        {
        }*/
        $users = User::with('userprofile')->where('church_id',Auth::user()->church_id)->ByRole(5)->get();
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        if(count($users) > 0)
        {
            $csv->insertOne(['firstname','lastname','gender','date_of_birth','profession','sub_occupation','address','city','state','country','pincode','mobile_no','email']);

            foreach($users as $user)
            {
                $csv->insertOne([
                    $user->userprofile->firstname,
                    $user->userprofile->lastname,
                    $user->userprofile->gender,
                    date('d-m-Y',strtotime($userprofile->date_of_birth)),
                    $user->userprofile->profession,
                    $user->userprofile->sub_occupation,
                    $user->userprofile->address,
                    $user->userprofile->city->name,
                    $user->userprofile->state->name,
                    $user->userprofile->country->name,
                    $user->userprofile->pincode,
                    $user->mobile_no,
                    $user->email,
                ]);
            }
        }
        else
        {
            $csv->insertOne(['No Records Found']);
            $csv->output('CS Member Export Birthday'.date('_d-m-Y_H:i').'.csv');
        }

        $csv->output('CS Member Export Birthday'.date('_d-m-Y_H:i').'.csv');

        $message=('Birthday Details Exported Successfully');

        $ip= $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
            LOGNAME_EXPORT_BIRTHDAY,
            $message
        );
    }

    public function exportAnniversary()
    {
        $userprofiles = Userprofile::with('user')->where([['church_id',Auth::user()->church_id],['marriage_status','married']])->ByRole(5)->get();
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        if(count($userprofiles) > 0)
        {
            $csv->insertOne(['firstname','lastname','gender','date_of_birth','profession','sub_occupation','address','city','state','country','pincode','mobile_no','email','marriage_status','marriage_start_date']);

            foreach($userprofiles as $userprofile)
            {
                $csv->insertOne([
                    $userprofile->firstname,
                    $userprofile->lastname,
                    $userprofile->gender,
                    date('d-m-Y',strtotime($userprofile->date_of_birth)),
                    $userprofile->profession,
                    $userprofile->sub_occupation,
                    $userprofile->address,
                    $userprofile->city->name,
                    $userprofile->state->name,
                    $userprofile->country->name,
                    $userprofile->pincode,
                    $userprofile->user->mobile_no,
                    $userprofile->user->email,
                    $userprofile->marriage_status,
                    $userprofile->marriage_start_date
                ]);
            }
        }
        else
        {
            $csv->insertOne(['No Records Found']);
            $csv->output('CS Member Export Anniversary'.date('_d-m-Y_H:i').'.csv');
        }

        $csv->output('CS Member Export Anniversary'.date('_d-m-Y_H:i').'.csv');

        $message=('Anniversary Details Exported Successfully');

        $ip= $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
            LOGNAME_EXPORT_ANNIVERSARY,
            $message
        );
    }

    public function exportActiveMembers()
    {
        $userprofiles = Userprofile::with('user')->where([['church_id',Auth::user()->church_id],['membership_type','member']])->ByRole(5)->get();
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        if(count($userprofiles) > 0)
        {
            $csv->insertOne(['ref_name','name','firstname','lastname','birth_firstname','birth_lastname','gender','date_of_birth','profession','sub_occupation','address','city','state','country','pincode','mobile_no','email','membership_type','membership_start_date','family','marriage_status','marriage_start_date','relation','notes','avatar','status']);

            foreach($userprofiles as $userprofile)
            {
                $payment_date = $userprofile->user->subscription['0']['payment_details']['addedon']==="" ? null:date('d-m-Y',strtotime($userprofile->user->subscription['0']['payment_details']['addedon']));
                $ref_user = User::where('id',$userprofile->user->ref_id)->first();
                $csv->insertOne([
                    $ref_user->FullName,
                    $userprofile->user->name,
                    $userprofile->firstname,
                    $userprofile->lastname,
                    $userprofile->birth_firstname,
                    $userprofile->birth_lastname,
                    $userprofile->gender,
                    date('d-m-Y',strtotime($userprofile->date_of_birth)),
                    $userprofile->profession,
                    $userprofile->sub_occupation,
                    $userprofile->address,
                    $userprofile->city->name,
                    $userprofile->state->name,
                    $userprofile->country->name,
                    $userprofile->pincode,
                    $userprofile->user->mobile_no,
                    $userprofile->user->email,
                    $userprofile->membership_type,
                    $userprofile->membership_start_date,
                    $userprofile->family,
                    $userprofile->marriage_status,
                    $userprofile->marriage_start_date,
                    $userprofile->relation,
                    $userprofile->notes,
                    url($userprofile->avatar),
                    $userprofile->status
                    
                ]);
            }
        }
        else
        {
            $csv->insertOne(['No Records Found']);
            $csv->output('CS Member Export ActiveMembers'.date('_d-m-Y_H:i').'.csv');
        }

        $csv->output('CS Member Export ActiveMembers'.date('_d-m-Y_H:i').'.csv');

        $message=('ActiveMembers Details Exported Successfully');

        $ip= $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
            LOGNAME_EXPORT_ACTIVE_MEMBERS,
            $message
        );
    }

    public function exportGuestMembers()
    {
        $userprofiles = Userprofile::with('user')->where([['church_id',Auth::user()->church_id],['membership_type','guest']])->ByRole(5)->get();
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        if(count($userprofiles) > 0)
        {
            $csv->insertOne(['ref_name','name','firstname','lastname','birth_firstname','birth_lastname','gender','date_of_birth','profession','sub_occupation','address','city','state','country','pincode','mobile_no','email','membership_type','membership_start_date','family','marriage_status','marriage_start_date','relation','notes','avatar','status']);

            foreach($userprofiles as $userprofile)
            {
                $ref_user = User::where('id',$userprofile->user->ref_id)->first();
                $csv->insertOne([
                    $ref_user->FullName,
                    $userprofile->user->name,
                    $userprofile->firstname,
                    $userprofile->lastname,
                    $userprofile->birth_firstname,
                    $userprofile->birth_lastname,
                    $userprofile->gender,
                    date('d-m-Y',strtotime($userprofile->date_of_birth)),
                    $userprofile->profession,
                    $userprofile->sub_occupation,
                    $userprofile->address,
                    $userprofile->city->name,
                    $userprofile->state->name,
                    $userprofile->country->name,
                    $userprofile->pincode,
                    $userprofile->user->mobile_no,
                    $userprofile->user->email,
                    $userprofile->membership_type,
                    $userprofile->membership_start_date,
                    $userprofile->family,
                    $userprofile->marriage_status,
                    $userprofile->marriage_start_date,
                    $userprofile->relation,
                    $userprofile->notes,
                    url($userprofile->avatar),
                    $userprofile->status
                ]);
            }
        }
        else
        {
            $csv->insertOne(['No Records Found']);
            $csv->output('CS Member Export GuestMembers'.date('_d-m-Y_H:i').'.csv');
        }

        $csv->output('CS Member Export GuestMembers'.date('_d-m-Y_H:i').'.csv');

        $message=('GuestMembers Details Exported Successfully');

        $ip= $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
            LOGNAME_EXPORT_GUEST_MEMBERS,
            $message
        );
    }

    public function exportSuspendedMembers()
    {
        $userprofiles = Userprofile::with('user')->where([['church_id',Auth::user()->church_id],['status','inactive']])->ByRole(5)->get();
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        if(count($userprofiles) > 0)
        {
            $csv->insertOne(['ref_name','name','firstname','lastname','birth_firstname','birth_lastname','gender','date_of_birth','profession','sub_occupation','address','city','state','country','pincode','mobile_no','email','membership_type','membership_start_date','family','marriage_status','marriage_start_date','relation','notes','avatar','status']);

            foreach($userprofiles as $userprofile)
            {
                $ref_user = User::where('id',$userprofile->user->ref_id)->first();
                $csv->insertOne([
                    $ref_user->FullName,
                    $userprofile->user->name,
                    $userprofile->firstname,
                    $userprofile->lastname,
                    $userprofile->birth_firstname,
                    $userprofile->birth_lastname,
                    $userprofile->gender,
                    date('d-m-Y',strtotime($userprofile->date_of_birth)),
                    $userprofile->profession,
                    $userprofile->sub_occupation,
                    $userprofile->address,
                    $userprofile->city->name,
                    $userprofile->state->name,
                    $userprofile->country->name,
                    $userprofile->pincode,
                    $userprofile->user->mobile_no,
                    $userprofile->user->email,
                    $userprofile->membership_type,
                    $userprofile->membership_start_date,
                    $userprofile->family,
                    $userprofile->marriage_status,
                    $userprofile->marriage_start_date,
                    $userprofile->relation,
                    $userprofile->notes,
                    url($userprofile->avatar),
                    $userprofile->status
                ]);
            }
        }
        else
        {
            $csv->insertOne(['No Records Found']);
            $csv->output('CS Member Export SuspendedMembers'.date('_d-m-Y_H:i').'.csv');
        }

        $csv->output('CS Member Export SuspendedMembers'.date('_d-m-Y_H:i').'.csv');

        $message=('SuspendedMembers Details Exported Successfully');

        $ip= $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
            LOGNAME_EXPORT_SUSPENDED_MEMBERS,
            $message
        );
    }
}
