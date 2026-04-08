<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        return view('theme::contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'mobile'   => 'nullable|string|max:30',
            'query'    => 'required|string|max:3000',
        ]);

        $church = $request->attributes->get('_church');

        Contact::create([
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

        return redirect()->back()->with('success', 'Thank you for reaching out. We will get back to you soon.');
    }
}
