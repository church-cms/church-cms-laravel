<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Resources\API\Guest\Events as EventResource;
use App\Http\Controllers\Controller;
use App\Models\Events;


class EventsController extends Controller
{
    public function index($church_id)
    {
        $events = Events::where('church_id', $church_id)->get();

        $events = EventResource::collection($events);

        return $events;
    }

    public function show($church_id, $id)
    {
        $event = Events::where([['church_id', $church_id], ['id', $id]])->first();

        return [
            'id'            =>  $event->id,
            'select_type'   =>  $event->select_type,
            'title'         =>  $event->title,
            'description'   =>  $event->description,
            'repeats'       =>  $event->repeats,
            'freq'          =>  $event->freq,
            'freq_term'     =>  $event->freq_term,
            'location'      =>  $event->location,
            'category'      =>  $event->category,
            'image'         =>  $event->ImagePath,
            'start_date'    =>  date('d M Y H:i:s', strtotime($event->start_date)),
            'end_date'      =>  date('d M Y H:i:s', strtotime($event->end_date)),
        ];
    }
}
