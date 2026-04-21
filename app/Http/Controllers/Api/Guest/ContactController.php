<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Requests\Api\Guest\ContactRequest;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Traits\Common;
use App\Models\User;
use Exception;
use Log;

class ContactController extends Controller
{
    use Common;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactRequest $request)
    {
        try {
            $contact = new Contact;

            $contact->church_id             = $request->church_id;
            $contact->fullname              = $request->fullname;
            $contact->email                 = $request->email;
            $contact->mobile                = $request->mobile;
            $contact->query                 = $request->query_message;
            $contact->date_of_submission    = date('Y-m-d H:i:s');

            $array = [];

            $array['ip'] = $this->getRequestIP();
            $array['details'] = $_SERVER['HTTP_USER_AGENT'];

            $contact->properties = $array;

            $contact->save();

            $user = User::ByRole(3)->first();

            if (env('MAIL_STATUS') === 'on') {
            }

            if ($contact != null) {
                $success = true;
                $message = 'Contact Submitted Successfully';
            } else {
                $success = false;
            }

            return response()->json([
                'status'    =>  $success,
                'message'   =>  $message,
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
