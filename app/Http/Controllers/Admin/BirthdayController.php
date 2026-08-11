<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Birthday as BirthdayResource;
use App\Http\Requests\BirthdayReminderRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Events\SinglePushEvent;
use App\Traits\EventProcess;
use App\Models\Userprofile;
use App\Traits\LogActivity;
use App\Models\Smstemplate;
use App\Traits\Common;
use Carbon\Carbon;
use Exception;
use Log;

/**
 * BirthdayController
 *
 * Manages birthday and anniversary reminders for church members.
 * Tracks member birthdays and anniversaries, sends reminders and notifications.
 * Provides listing and management of upcoming birthday/anniversary events.
 *
 * @package App\Http\Controllers\Admin
 * @uses EventProcess Trait for event-related processing
 * @uses LogActivity Trait for activity tracking
 * @uses Common Trait for utility functions
 */
class BirthdayController extends Controller
{
    //
    use EventProcess;
    use LogActivity;
    use Common;

    public function showBirthday()
    {
        //
        $birthday = Userprofile::with('user')
                            ->whereRaw("DATE_FORMAT(date_of_birth, '%m-%d') = DATE_FORMAT(now(),'%m-%d')")
                            ->ByChurch(Auth::user()->church_id)
                            ->ByRole(5)
                            ->get();

        $users = BirthdayResource::collection($birthday);

        return $users;
    }

    public function birthdayUser()
    {
        $array = [];

        $birthday = Userprofile::with('user')
                            ->whereRaw("DATE_FORMAT(date_of_birth, '%m-%d') = DATE_FORMAT(now(),'%m-%d')")
                            ->ByChurch(Auth::user()->church_id)
                            ->ByRole(5)
                            ->get();
        $birthday = BirthdayResource::collection($birthday);

        $templates = Smstemplate::where('name','birthday_message')->get();

        $array['birthdaylist'] = $birthday;
        $array['templatelist'] = $templates;

        return $array;
    }

    public function birthday()
    {
        return view('/admin/dashboard/birthday');
    }

    public function birthdayMessage(BirthdayReminderRequest $request)
    {
        try
        {
            $church_id = Auth::user()->church_id;
            $data = array('subject'=>'Birthday Wishes' , 'message'=>$request->message , 'type'=>'birthday');

            foreach($request->to as $to)
            {
                $mobile_no = $to['mobile_no'];
                $mail = $to['email'];
                $entity_id = $to['id'];
                $send = $to['user'];
                $month = date('m-d',strtotime($to['date_of_birth']));
                $current_year=date('Y');
                $date_of_birth = $current_year.'-'.$month;
                $marriage_start_date=null;

                $this->sendToBirthdayReminder($church_id,$entity_id,$date_of_birth,$marriage_start_date,$data,$mobile_no,$mail);
                $data=[];

                $data['church_id']  =   Auth::user()->church_id;
                $data['user_id']    =   $to['id'];
                $data['message']    =   $request->message;
                $data['type']       =   'birthday';

                event(new SinglePushEvent($data));
            }

            $res['success']="Birthday Wishes Sent Successfully";
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function showAnniversary()
    {
        //
        $anniversary = Userprofile::with('user')
                            ->whereRaw("DATE_FORMAT(marriage_start_date, '%m-%d') = DATE_FORMAT(now(),'%m-%d')")
                            ->ByChurch(Auth::user()->church_id)
                            ->ByRole(5)
                            ->get();

        $users = AnniversaryResource::collection($anniversary);

        return $users;
    }

    public function anniversaryUser()
    {
        $array = [];

        $anniversary = Userprofile::with('user')
                            ->whereRaw("DATE_FORMAT(marriage_start_date, '%m-%d') = DATE_FORMAT(now(),'%m-%d')")
                            ->ByChurch(Auth::user()->church_id)
                            ->ByRole(5)
                            ->get();
        $anniversary = AnniversaryUserResource::collection($anniversary);
        $templates = Smstemplate::where('name','anniversary_message')->get();

        $array['anniversarylist'] = $anniversary;
        $array['templatelist'] = $templates;

        return $array;

    }

    public function anniversary()
    {
        return view('/admin/dashboard/anniversary');
    }

    public function anniversaryMessage(BirthdayReminderRequest $request)
    {
        try
        {
            $church_id = Auth::user()->church_id;
            $data = array('subject'=>'Anniversary Wishes' , 'message'=>$request->message , 'type'=>'anniversary');

            foreach($request->to as $to)
            {
                $mobile_no = $to['mobile_no'];
                $mail = $to['email'];
                $entity_id = $to['id'];
                $month = date('m-d',strtotime($to['marriage_start_date']));
                $current_year=date('Y');
                $marriage_start_date = $current_year.'-'.$month;
                $date_of_birth=null;

                $this->sendToBirthdayReminder($church_id,$entity_id,$date_of_birth,$marriage_start_date,$data,$mobile_no,$mail);

                $data=[];

                $data['church_id']  =   Auth::user()->church_id;
                $data['user_id']    =   $to['id'];
                $data['message']    =   $request->message;
                $data['type']       =   'anniversary';

                event(new SinglePushEvent($data));
            }

            $res['success']="Anniversary Wishes Sent Successfully";
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
