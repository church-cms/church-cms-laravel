<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\MemberProcess;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Traits\Common;
use League\Csv\Writer;
use App\Models\User;
use SplFileObject;
use Exception;
use Excel;
use DB;

/**
 * ExportMemberController
 *
 * Handles member data export functionality.
 * Provides CSV export of member records with filtering options by roles and groups.
 * Supports bulk export of member information for data management and backup.
 *
 * @package App\Http\Controllers\Admin
 * @uses MemberProcess Trait for member filtering and processing
 * @uses LogActivity Trait for activity tracking
 * @uses Common Trait for utility functions
 */
class ExportMemberController extends Controller
{
    use MemberProcess;
    use LogActivity;
    use Common;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/member/export/export');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportUsers(Request $request)
    {
        $users = $this->MemberFilter($request,Auth::user()->church_id,$request->usergroup_id);
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

       //dd($request->usergroup_id);
 
        if(count($users) > 0)
        {
            
            if($request->usergroup_id == 5)
            {
          
 
                $csv->insertOne(['ref_name','firstname','lastname','birth_firstname','birth_lastname','gender','date_of_birth','profession','sub_occupation','address','city','state','country','pincode','mobile_no','email','membership_type','membership_start_date','family','marriage_status','marriage_start_date','relation','notes','status']);

                foreach($users as $user)
                {
                    $ref_name = User::where('id',$user->ref_id)->first();
                    $csv->insertOne([
                        $ref_name->name,
                        $user->userprofile->firstname,
                        $user->userprofile->lastname,
                        $user->userprofile->birth_firstname,
                        $user->userprofile->birth_lastname,
                        $user->userprofile->gender,
                        date('d-m-Y',strtotime($user->userprofile->date_of_birth)),
                        $user->userprofile->profession,
                        $user->userprofile->sub_occupation,
                        $user->userprofile->address,
                        $user->userprofile->city->name,
                        $user->userprofile->state->name,
                        $user->userprofile->country->name,
                        $user->userprofile->pincode,
                        $user->mobile_no,
                        $user->email,
                        $user->userprofile->membership_type,
                        $user->userprofile->membership_start_date,
                        $user->userprofile->family,
                        $user->userprofile->marriage_status,
                        $user->userprofile->marriage_start_date,
                        $user->userprofile->relation,
                        $user->userprofile->notes,
                        $user->userprofile->status
                    ]);
                }

             
                $csv->output('CS Member Export'.date('_d_m_Y_H_i_s').'.csv');
                $message=('Member Details Exported Successfully');
                $log = LOGNAME_EXPORT_MEMBER;
            }
            elseif ($request->usergroup_id == 4)
            {

                

                $csv->insertOne(['firstname','lastname','birth_firstname','birth_lastname','aadhar_number','date_of_birth','mobile_no','email','gender','profession','sub_occupation','address','country','state','city','pincode','notes','status']);

                foreach($users as $user)
                {
                    $ref_name = User::where('id',$user->ref_id)->first();
                    $csv->insertOne([
                        $user->userprofile->firstname,
                        $user->userprofile->lastname,
                        $user->userprofile->birth_firstname,
                        $user->userprofile->birth_lastname,
                        $user->userprofile->aadhar_number,
                        date('d-m-Y',strtotime($user->userprofile->date_of_birth)),
                        $user->mobile_no,
                        $user->email,
                        $user->userprofile->gender,
                        $user->userprofile->profession,
                        $user->userprofile->sub_occupation,
                        $user->userprofile->address,
                        $user->userprofile->country->name,
                        $user->userprofile->state->name,
                        $user->userprofile->city->name,
                        $user->userprofile->pincode,
                        $user->userprofile->notes,
                        $user->userprofile->status,
                    ]);
                }
                $csv->output('CS Sub Admin Export'.date('_d_m_Y_H_i_s').'.csv');
                $message=('Sub Admin Details Exported Successfully');
                $log = LOGNAME_EXPORT_SUB_ADMIN;
            }
        }
        else
        {
            $csv->insertOne(['No Records Found']);
            $csv->output('CS Member Export'.date('_d_m_Y_H_i_s').'.csv');
        }
          
        $ip= $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
            $log,
            $message
        );
        //\Session::put('successmessage','Member Exported Successfully');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportGuests(Request $request)
    {
        $users = $this->GuestFilter($request,Auth::user()->church_id,$request->usergroup_id);
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        if(count($users) > 0)
        {
            $csv->insertOne(['firstname','lastname','gender','date_of_birth','occupation','sub_occupation','address','city','state','country','pincode','mobile_no','email','notes','status']);

            foreach($users as $user)
            {
                $csv->insertOne([
                    $user->userprofile->firstname,
                    $user->userprofile->lastname,
                    $user->userprofile->gender,
                    date('d-m-Y',strtotime($user->userprofile->date_of_birth)),
                    $user->userprofile->profession,
                    $user->userprofile->sub_occupation,
                    $user->userprofile->address,
                    $user->userprofile->city->name,
                    $user->userprofile->state->name,
                    $user->userprofile->country->name,
                    $user->userprofile->pincode,
                    $user->mobile_no,
                    $user->email,
                    $user->userprofile->notes,
                    $user->userprofile->status,
                ]);
            }
        }
        else
        {
            $csv->insertOne(['No Records Found']);
            $csv->output('CS Guest Export'.date('_d_m_Y_H_i_s').'.csv');
        }
        $csv->output('CS Guest Export'.date('_d_m_Y_H_i_s').'.csv');
        $message=('Guest Details Exported Successfully');

        $ip= $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
            LOGNAME_EXPORT_GUEST,
            $message
        );
        //\Session::put('successmessage','Member Exported Successfully');
    }
}
