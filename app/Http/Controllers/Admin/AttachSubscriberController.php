<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Mailinglist as MailinglistResource;
use App\Http\Requests\MaillistSubscriberRequest;
use App\Http\Requests\ImportMailingListRequest;
use App\Models\MailinglistSubscriber;
use App\Events\ImportSubscriberEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Models\MailingList;
use App\Models\Subscribers;
use App\Traits\Common;
use Carbon\Carbon;
use Exception;
use Log;

/**
 * AttachSubscriberController
 *
 * Manages the process of attaching subscribers to mailing lists.
 * Provides functionality to add individual subscribers, handle bulk imports,
 * and manage subscriber-mailing list associations.
 * Logs activity for audit trails using LogActivity trait.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for activity logging
 * @uses Common Trait for utility methods
 */
class AttachSubscriberController extends Controller
{
    use LogActivity;
    use Common;

    public function create($id)
    {
        if($_SERVER['HTTP_REFERER'] != null)
        {
            $prev_url = $_SERVER['HTTP_REFERER'];
        }
        else
        {
            $prev_url = url('/admin/mailinglist/show/'.$id);
        }

        return view('/admin/mailinglist/attachsubscriber',['mailinglist_id' => $id , 'prev_url' => $prev_url]);
    }

    public function store(MaillistSubscriberRequest $request,$id)
    {
        try
        {
            $subscriber=Subscribers::where('id',$request->subscriber_id)->first();

            $maillist=MailingList::where('id',$id)->first();

            $maillist->subscribers()->sync($subscriber,false);

            $message = 'Subscriber Attached Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $maillist,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ATTACH_SUBSCRIBER_TO_MAILINGLIST,
                $message
            );

            $res['success'] ='Subscriber Attached To MailingList Successfully';
            return $res;
        }
        catch( Exception $e )
        {
            Log::info($e->getMessage());

        }
    }

    public function show($id)
    {
        $mailinglist = MailingList::where('id','!=',$id)->get();

        $mailinglist= MailinglistResource::collection($mailinglist);

        return $mailinglist;
    }

    public function importsubscriber(ImportMailingListRequest $request)
    {
        try
        {
            if(count($request->mail_list) > 0)
            {
                $subscribers = MailinglistSubscriber::whereIn('mailing_list_id',$request->mail_list)->groupBy('subscribers_id')->pluck('subscribers_id')->toArray();

                $mailinglist = MailingList::where('id',$request->id)->first();

                $mailinglist->subscribers()->sync($subscribers,false);

                foreach($subscribers as $subscriber)
                {
                    $check = MailinglistSubscriber::where([['mailing_list_id',$mailinglist->id],['subscribers_id',$subscriber]])->first();
                    if($check === null)
                    {
                        $mailinglistSubscriber = new MailinglistSubscriber;

                        $mailinglistSubscriber->mailing_list_id  =   $mailinglist->id;
                        $mailinglistSubscriber->subscribers_id   =   $subscriber;

                        $mailinglistSubscriber->save();

                        $res['success'] = "Imported Successfully";
                    }
                    else
                    {
                        $res['success'] = "Mailinglist Subscribers Already Exists";
                    }
                }

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    $mailinglist,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_IMPORT_SUBSCRIBER_TO_MAILINGLIST,
                    $message
                );
            }
            else
            {
                $res['success']="";
            }
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function importcsv(Request $request,$id)
    {
        try
        {
            $last_sub=Subscribers::orderBy('id','desc')->first();
            $mailinglist = MailingList::where('id',$id)->first();
            $duplicate=[];
            $error=[];
            $success=[];
            $res=[];
            $church_id=Auth::user()->church_id;
            $created_at=Carbon::now();
            $updated_at=Carbon::now();
            $emails=[];
            $i=0;

            foreach($request->csv as $csv)
            {
                $subscribers=Subscribers::where('email',$csv['email'])->exists();
                if(!$subscribers && (!in_array(strtolower($csv['email']),$emails)))
                {
                    if($this->valid_email($csv['email']))
                    {
                        $res[$i]['church_id']   =   $church_id;
                        $res[$i]['firstname']   =   $csv['firstname'];
                        $res[$i]['lastname']    =   $csv['lastname'];
                        $res[$i]['email']       =   $csv['email'];
                        $res[$i]['aff']         =   $csv['aff'];
                        $res[$i]['source']      =   $csv['source'];
                        $res[$i]['is_active']   =   $csv['active'];
                        $success[]              =   $csv['email'];
                        $emails[]               =   strtolower($csv['email']);//for duplicate check
                    }
                    else
                    {
                        $error[] = $csv['email'];
                    }
                }
                else
                {
                    $duplicate[] = $csv['email'];
                }
                $i++;
            }

            //Insert
            if(count($res)>0)
            {
                \DB::table('subscribers')->insert($res);

                if(count($last_sub)>0)
                {
                    $sub=Subscribers::where('id','>',$last_sub->id)->pluck('id')->toArray();
                }
                else
                {
                    $sub=Subscribers::pluck('id')->toArray();
                }

                if(count($sub)>0)
                {
                    $update=[
                        'created_at'=>$created_at,
                        'updated_at'=>$updated_at,
                    ];

                    foreach($sub as $subscriber_id)
                    {
                        $mailinglistSubscriber = new MailinglistSubscriber;

                        $mailinglistSubscriber->mailing_list_id  =   $id;
                        $mailinglistSubscriber->subscribers_id   =   $subscriber_id;

                        $mailinglistSubscriber->save();

                        event(new ImportSubscriberEvent($subscriber_id,$update));
                    }

                    $mailinglist->subscribers()->sync($sub,false);

                    event(new ImportSubscriberEvent($sub,$update));
                }
            }

            $file='uploads/success'.date('_d-m-Y_H_i_s').'.csv';
            $file_open = fopen($file, 'w');
            fputcsv($file_open, $success);
            $successpath=$file;
            //Error
            $file='uploads/error'.date('_d-m-Y_H_i_s').'.csv';
            $file_open = fopen($file, 'w');
            fputcsv($file_open, $error);
            $errorpath=$file;
            //Duplicate
            $file='uploads/duplicate'.date('_d-m-Y_H_i_s').'.csv';
            $file_open = fopen($file, 'w');
            fputcsv($file_open, $duplicate);
            $duplicatepath=$file;//filepath

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $mailinglist,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_IMPORT_SUBSCRIBER_VIA_CSV_TO_MAILINGLIST,
                $message
            );

            $data=[];
            $data['success_path']       =   url($successpath);
            $data['success_count']      =   count($success);
            $data['duplicate_path']     =   url($duplicatepath);
            $data['duplicate_count']    =   count($duplicate);
            $data['error_path']         =   url($errorpath);
            $data['error_count']        =   count($error);
            $data['message']            =   'Subscriber Imported Successfully';

            return $data;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function valid_email($email)
    {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) ? FALSE : TRUE;
    }
}
