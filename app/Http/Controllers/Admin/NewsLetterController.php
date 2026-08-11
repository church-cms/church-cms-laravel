<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\NewsletterRequest;
use App\Events\SubscribeNewsLetterEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterMail;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Models\NewsLetter;
use App\Traits\Common;
use App\Models\User;
use Exception;
use Log;

/**
 * NewsLetterController
 *
 * Manages church newsletter creation and distribution.
 * Handles newsletter content creation, sending to all or specific subscribers,
 * and integration with email queue system for asynchronous delivery.
 * Fires newsletter subscription events for tracking engagement.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for recording newsletter actions
 * @uses Common Trait for helper functions
 */
class NewsLetterController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('/admin/newsletter/send');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsletterRequest $request)
    {
        //
        try {
            if ($request->to === 1) {
                $newsletters = NewsLetter::where([['church_id', Auth::user()->church_id], ['status', 1]])->get();
            }

            if ($request->to === 0) {
                $newsletters = NewsLetter::where([['church_id', Auth::user()->church_id], ['status', 0]])->get();
            }

            foreach ($newsletters as $newsletter) {
                Mail::to($newsletter->email)->queue(new NewsletterMail($request->subject, $request->message));

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    $newsletter,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_SEND_NEWSLETTER,
                    'NewsLetter Sent Successfully'
                );
            }

            $res['success'] = 'NewsLetter Sent Successfully';
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateMemberStatus(Request $request, $name)
    {
        try {
            $user = User::where('name', $name)->first();
            $newsletter = NewsLetter::where('email', $user->email)->first();

            if ($newsletter != null) {
                $newsletter->status = $request->status;

                $newsletter->save();
            } else {
                $newsletter = new NewsLetter;

                $newsletter->church_id  = Auth::user()->church_id;
                $newsletter->name       = $user->name;
                $newsletter->email      = $user->email;
                $newsletter->status     = $request->status;

                $newsletter->save();
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $newsletter,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_CHANGE_NEWSLETTER_STATUS,
                'NewsLetter Status Updated Successfully'
            );

            return redirect()->back();
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request)
    {
        try {

           

            //dd($request);
            event(new SubscribeNewsLetterEvent($request, Auth::user()->church_id, Auth::user()));


            $res['message'] = 'NewsLetter Status Updated Successfully';
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
