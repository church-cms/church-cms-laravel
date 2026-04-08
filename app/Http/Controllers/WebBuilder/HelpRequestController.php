<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Help;
use Illuminate\Http\Request;

class HelpRequestController extends Controller
{
    public function index()
    {
        $requests = Help::where('status', 'approve')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        return view('theme::help_index', compact('requests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string|max:3000',
            'contact_details' => 'required|string|max:500',
        ]);

        $church = $request->attributes->get('_church');

        Help::create([
            'church_id'       => optional($church)->id,
            'user_id'         => null,
            'title'           => $validated['title'],
            'description'     => $validated['description'],
            'contact_details' => $validated['contact_details'],
            'status'          => 'open',
        ]);

        return redirect()->back()->with('success', 'Your help request has been submitted.');
    }
}
