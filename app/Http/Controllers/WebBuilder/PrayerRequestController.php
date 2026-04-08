<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\PrayerRequest;
use Illuminate\Http\Request;

class PrayerRequestController extends Controller
{
    public function index()
    {
        $requests = PrayerRequest::where('status', 'approve')
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(10);

        return view('theme::prayer_index', compact('requests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:3000',
        ]);

        $church = $request->attributes->get('_church');

        PrayerRequest::create([
            'church_id'    => optional($church)->id,
            'user_id'      => null,
            'title'        => $validated['title'],
            'description'  => $validated['description'],
            'is_anonymous' => 1,
            'status'       => 'open',
        ]);

        return redirect()->back()->with('success', 'Your prayer request has been submitted.');
    }
}
