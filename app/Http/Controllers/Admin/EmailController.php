<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Email as EmailResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\EmailRequest;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\Email;
use Exception;
use Log;

/**
 * EmailController
 *
 * Manages email templates for church communications.
 * Handles email template creation, updates, and deletion.
 * Provides email templates for various notifications and communications.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class EmailController extends Controller
{
    use LogActivity;
    use Common;

    public function index()
    {
        return view('/admin/email/index');
    }

    public function list()
    {
        $list = Email::where('church_id',Auth::user()->church_id)->get();

        $emaillist = EmailResource::collection($list);

        return $emaillist;
    }

    public function create()
    {
        return view('/admin/email/create');
    }

    public function store(EmailRequest $request)
    {
        try
        {
            $email = new Email;

            $email->church_id       = Auth::user()->church_id;
            $email->subject         = $request->subject;
            $email->from_email      = $request->from_email;
            $email->from_name       = $request->from_name;
            $email->reply_to_email  = $request->reply_to_email;
            $email->content         = $request->content;

            $email->save();

            $message = 'Email Created Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $email,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_EMAIL,
                $message
            );

            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function edit($id)
    {
        $email = Email::where('id',$id)->first();

        return view('/admin/email/edit',['email' => $email]);
    }

    public function update(EmailRequest $request,$id)
    {
        try
        {
        	$email = Email::where('id',$id)->first();

            $email->subject         = $request->subject;
            $email->from_email      = $request->from_email;
            $email->from_name       = $request->from_name;
            $email->reply_to_email  = $request->reply_to_email;
            $email->content         = $request->content;

        	$email->save();

            $message = 'Email Updated Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $email,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_EMAIL,
                $message
            );

            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function show($id)
    {
        $email = Email::where([['id',$id],['church_id',Auth::user()->church_id]])->first();

        if($_SERVER['HTTP_REFERER'] != null)
        {
            $prev_url = $_SERVER['HTTP_REFERER'];
        }
        else
        {
            $prev_url = url('/admin/emails');
        }

        return view('/admin/email/show',['email' => $email , 'prev_url' => $prev_url]);
    }

    public function showDetails($id)
    {
        $email = Email::where('id',$id)->get();
        $email = EmailResource::collection($email);
        return $email;
    }

   /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try
        {
            $email = Email::where('id',$id)->first();

            $email->delete();

            $message = 'Email Deleted Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $email,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_EMAIL,
                $message
            );

            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
