<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Models\User;
use App\Models\Widget;
use App\Rules\ValidRecaptcha;

class ContactController extends Controller
{
    public function show()
    {
        $widgets = Widget::where('page', 'contact')
            ->orderBy('display_order')
            ->get();

        $topwidget = $widgets->where('position', 'top');
        $bottomwidget = $widgets->where('position', 'bottom');


        return view('theme::contact', compact('topwidget', 'bottomwidget'));
    }

    public function store(Request $request)
    {
        $rules = [
            'fullname' => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'mobile'   => 'nullable|string|max:30',
            'query'    => 'required|string|max:3000',
        ];

        if (config('settings.contact_captcha_status') == "1") {
            $rules['g-recaptcha-response'] = ['required', new ValidRecaptcha()];
        }

        $validated = $request->validate($rules);



        $church = $request->attributes->get('_church');

        $contact = Contact::create([
            'church_id'          => optional($church)->id,
            'fullname'           => $validated['fullname'],
            'email'              => $validated['email'],
            'mobile'             => $validated['mobile'] ?? null,
            'query'              => $validated['query'],
            'date_of_submission' => now(),
            'properties'         => json_encode([
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]),
        ]);

        $user = User::ByRole(3)->first();

        if (env('MAIL_STATUS') === 'on') {
            Mail::to($user->email)->send(new ContactMail($contact));
        }

        return redirect()->back()->with('success', 'Thank you for reaching out. We will get back to you soon.');
    }
}
