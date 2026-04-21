<?php

namespace App\Http\Controllers\Api\Angular;

use App\Http\Requests\ContactRequest;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Traits\Common;
use App\Models\Church;
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
    public function store(ContactRequest $request, $slug)
    {
        try {
            $church = Church::where('slug', '=', $slug)->first();

            $contact = new Contact;

            $contact->church_id             = $church->id;
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
