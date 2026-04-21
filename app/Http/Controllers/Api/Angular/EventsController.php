<?php

namespace App\Http\Controllers\Api\Angular;

use App\Http\Resources\API\Angular\ShowEvent as ShowEventResource;
use App\Http\Controllers\Controller;
use App\Models\Events;
use App\Models\Church;
use Carbon\Carbon;

class EventsController extends Controller
{

    public function showEvents($slug)
    {
        $church = Church::where('slug', '=', $slug)->first();

        $events = Events::where('church_id', $church->id)->get();
        $events = ShowEventResource::collection($events);

        return $events;
    }

    public function showUpcomingEventsMonthly($slug)
    {
        $church = Church::where('slug', '=', $slug)->first();

        /*$events = Events::where([['church_id',$church->id],['end_date','>=',Carbon::now()]])->orderBy('start_date',ASC)->get()->groupBy([function($event) {
                return Carbon::parse($event->start_date)->format('M Y'); 
            }]);*/
        $events = Events::where([['church_id', $church->id], ['end_date', '>=', Carbon::now()]])->orderBy('start_date', ASC)->take(10)->get();
        $events = ShowEventResource::collection($events);

        return $events;
    }

    public function showPastEventsMonthly($slug)
    {
        $church = Church::where('slug', '=', $slug)->first();

        /*$events = Events::where([['church_id',$church->id],['end_date','<',Carbon::now()]])->orderBy('start_date',DESC)->get()->groupBy([function($event) {
                return Carbon::parse($event->start_date)->format('M Y'); 
            }]);*/
        $events = Events::where([['church_id', $church->id], ['end_date', '<', Carbon::now()]])->orderBy('start_date', DESC)->take(5)->get();
        $events = ShowEventResource::collection($events);

        return $events;
    }

    public function showById($slug, $id)
    {
        $church = Church::where('slug', '=', $slug)->first();

        $event = Events::where([['church_id', $church->id], ['id', $id]])->first();

        $array = [];

        $array['event_id']      =  $event->id;
        $array['church_id']     =  $event->church_id;
        $array['select_type']   =  $event->select_type;
        $array['title']         =  $event->title;
        $array['description']   =  $event->description;
        $array['repeats']       =  $event->repeats;
        $array['freq']          =  $event->freq;
        $array['freq_term']     =  $event->freq_term;
        $array['location']      =  $event->location;
        $array['category']      =  $event->category;
        $array['image']         =  $event->ImagePath;
        $array['start_date']    =  date('d-m-Y H:i:s', strtotime($event->start_date));
        $array['end_date']      =  date('d-m-Y H:i:s', strtotime($event->end_date));

        return $array;
    }

    public function showLatestEvents($slug)
    {
        $church = Church::where('slug', '=', $slug)->first();
        $events = Events::where('church_id', $church->id)->take(3)->latest('start_date')->get();
        $events = ShowEventResource::collection($events);
        return $events;
    }

    public function getEventDetail($slug, $id)
    {
        $church = Church::where('slug', '=', $slug)->first();
        $icodetail = Events::where([['church_id', $church->id], ['id', $id]])->first();
        return new ShowEventResource($icodetail);
    }
}
