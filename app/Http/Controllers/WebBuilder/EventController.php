<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $upcoming = Events::where('start_date', '>=', $today)
                          ->where('select_type', 'public')
                          ->orderBy('start_date', 'asc')
                          ->paginate(9, ['*'], 'upcoming_page');

        $completedRaw = Events::where('start_date', '<', $today)
                              ->where('select_type', 'public')
                              ->orderBy('start_date', 'desc')
                              ->get();

        // Group: [ year => [ 'M Y' => [ events ] ] ]
        $completed = [];
        foreach ($completedRaw as $event) {
            $dt    = Carbon::parse($event->start_date);
            $year  = $dt->format('Y');
            $month = $dt->format('F');          // e.g. "March"
            $completed[$year][$month][] = $event;
        }

        return view('theme::events', compact('upcoming', 'completed'));
    }

    public function show($id)
    {
        $event = Events::with('gallery')->findOrFail($id);

        return view('theme::event', compact('event'));
    }
}
