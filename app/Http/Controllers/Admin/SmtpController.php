<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Smtps as SmtpsResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SmtpRequest;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\Smtp;
use Exception;
use Log;

/**
 * SmtpController
 *
 * Manages SMTP email server configurations.
 * Handles SMTP setup, configuration updates, and provider credential management.
 * Stores email server connection details (host, port, credentials) for outgoing mail.
 * Supports multiple SMTP configurations per church.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class SmtpController extends Controller
{
    use LogActivity;
    use Common;

    public function index()
    {
        return view('/admin/smtp/index');
    }

    public function list()
    {
        $smtplist = Smtp::where('church_id', Auth::user()->church_id)->get();

        return SmtpsResource::collection($smtplist);
    }

    public function create()
    {
        return view('/admin/smtp/create');
    }

    public function store(SmtpRequest $request)
    {
        try
        {
            $smtp = new Smtp;

            $smtp->church_id    = Auth::user()->church_id;
            $smtp->host         = $request->host;
            $smtp->port         = $request->port;
            $smtp->username     = $request->username;
            $smtp->password     = $request->password;
            $smtp->encryption   = $request->encryption;
            if($request->status === 'false')
            {
                $smtp->status   = 0;
            }
            else
            {
                $smtp->status   = $request->status;
            }

            $smtp->save();

            $message = 'Smtp Created Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $smtp,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_SMTP,
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
        $smtp = Smtp::where( [['id', $id], ['church_id', Auth::user()->church_id]] )->first();

        return view('/admin/smtp/show',['smtp' => $smtp]);
    }

    public function editList($id)
    {
        $smtp = Smtp::where('id', $id)->first();

        $array = [];

        $array['id']         =  $smtp->id;
        $array['host']       =  $smtp->host;
        $array['port']       =  $smtp->port;
        $array['username']   =  $smtp->username;
        $array['password']   =  $smtp->password;
        $array['encryption'] =  $smtp->encryption;
        $array['status']     =  $smtp->status;

        return $array;
    }

    public function edit($id)
    {
        $smtp = Smtp::where('id', $id)->first();

        return view('/admin/smtp/edit', ['smtp' => $smtp]);
    }

    public function update(SmtpRequest $request, $id)
    {
        try
        {
            $smtp = Smtp::where('id', $id)->first();

            $smtp->host         = $request->host;
            $smtp->port         = $request->port;
            $smtp->username     = $request->username;
            $smtp->password     = $request->password;
            $smtp->encryption   = $request->encryption;
            if($request->status === 'false')
            {
                $smtp->status   = 0;
            }
            else
            {
                $smtp->status   = $request->status;
            }

            $smtp->save();

            $message = 'Smtp Updated Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $smtp,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_SMTP,
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
            $smtp = Smtp::where( 'id', $id )->first();

            $smtp->delete();

            $message = 'Smtp Deleted Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $smtp,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_SMTP,
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
