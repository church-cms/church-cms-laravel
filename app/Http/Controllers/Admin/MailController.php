<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests\TestSendMailRequest;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Mail\ListingMail;
use App\Models\Email;
use Exception;
use Log;

/**
 * MailController
 *
 * Handles email testing and email template management.
 * Allows sending test emails to verify email configurations and templates.
 * Integrates with email queue system for asynchronous mail delivery.
 *
 * @package App\Http\Controllers\Admin
 */
class MailController extends Controller
{
    public function store(TestSendMailRequest $request)
    {
        try
        {
            $email=Email::where('id',$request->id)->first();

            Mail::to($request->to_email)->queue(new ListingMail($email->subject,$email->from_email,$email->from_name,$email->reply_to_email,$email->content));

            $res['message'] = "Test Mail Send Successfully";
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
