<?php

namespace App\Http\Controllers\Siteadmin;

use App\Models\Contact;
use App\Http\Controllers\Controller;

/**
 * ContactController
 *
 * Manages contact inquiries and messages for siteadmin.
 * Handles CRUD operations for system contact submissions.
 * Processes mail notifications and contact management.
 *
 * @package App\Http\Controllers\Siteadmin
 */
class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::paginate(10);

        return view('site_admin.contacts.show',[
                        'contacts'=>$contacts,

            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       /* return view('welcome.contact');*/
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactRequest $request)
    {


      /*  $contact = new Contact;
        $contact->fullname = $request->fullname;
        $contact->email = $request->emailid;
        $contact->serve_at = $request->serve_at;
        $contact->role = $request->role;
        $contact->contact_no = $request->contact_no;

        $contact->select = $request->select;
        $contact->save();

        $user=User::find(1);


         Mail::to($user->email)->send(new ContactMail($contact));

         $message=__('notes.notes_message');



         $res['message']=__('notes.notes_message');
        return $res;
        */
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $contacts = Contact::where('id',$id)->first();
        return view('site_admin.contacts.contact',[

    'contacts'=>$contacts,

 ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        //
    }
}
