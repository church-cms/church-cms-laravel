<?php

namespace App\Http\Controllers\Admin\EmailBlaster;

use App\Http\Resources\MailQueue as MailQueueResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\MailQueue;

/**
 * MailsDeliveredController
 *
 * Displays email delivery status and delivered emails for email blaster campaigns.
 * Shows mail queue entries with delivery information and status tracking.
 * Provides insights into email campaign delivery performance.
 *
 * @package App\Http\Controllers\Admin\EmailBlaster
 */
class MailsDeliveredController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('/admin/emailblaster/mailsdelivered/index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        //
        $mailqueue = MailQueue::ByChurch(Auth::user()->church_id)->orderby('id','desc')->where('fired_at','!=',null)->get();

        $mailqueue= MailQueueResource::collection($mailqueue);

        return $mailqueue;
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
        $mailqueue = MailQueue::where([['id',$id],['fired_at','!=',null]])->first();

        if($_SERVER['HTTP_REFERER'] != null)
        {
            $prev_url = $_SERVER['HTTP_REFERER'];
        }
        else
        {
            $prev_url = url('/admin/mailqueues');
        }

        return view('/admin/emailblaster/mailsdelivered/show',['mailqueue' => $mailqueue , 'prev_url' => $prev_url]);
    }
}
