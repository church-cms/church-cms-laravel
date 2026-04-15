<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ShowEventGallery as ShowEventGalleryResource;
use App\Http\Resources\Attendance as AttendanceResource;
use App\Http\Resources\EditEvent as EditEventResource;
use App\Http\Resources\ShowEvent as ShowEventResource;
use App\Events\Notification\PushNotificationEvent;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Traits\SendPushNotification;
use App\Http\Requests\EventRequest;
use App\Traits\ReminderProcess;
use App\Events\CalendarEvent;
use App\Events\ReminderEvent;
use App\Traits\EventProcess;
use App\Models\EventGallery;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Models\Attendance;
use App\Events\PushEvent;
use App\Traits\Common;
use App\Models\Events;
use App\Models\User;
use Exception;
use Log;

/**
 * EventsController
 *
 * Manages church events and activities with comprehensive event tracking.
 * Handles event creation, updates, attendance tracking, reminders, and push notifications.
 * Supports recurring events, event galleries, and subscription-based features.
 * Integrates with calendar system, attendance tracking, and member notifications.
 *
 * @package App\Http\Controllers\Admin
 * @uses SendPushNotification Trait for mobile push notifications
 * @uses ReminderProcess Trait for event reminder scheduling
 * @uses EventProcess Trait for event business logic
 * @uses LogActivity Trait for audit trail
 * @uses Common Trait for file and utility helpers
 */
class EventsController extends Controller
{
    use SendPushNotification;
    use ReminderProcess;
    use EventProcess;
    use LogActivity;
    use Common;

    public function index()
    {
        $events = Events::where('church_id',Auth::user()->church_id)->get();
        $count  = $events->count();
        
        $events = $events->map(function( $event, $key) {
            $eventData = [
                'id'        =>  $event->id,
                'title'     =>  $event->title,
                'start'     =>  date('Y-m-d', strtotime($event->start_date)).'T'.date('H:i:s', strtotime($event->start_date)),
                'end'       =>  date('Y-m-d', strtotime($event->end_date)).'T'.date('H:i:s', strtotime($event->end_date)),
                'allDay'    =>  $event->allDay
            ];
            return $eventData;
        });
        $events = json_encode($events);

        return view('admin.events.index',['events'=>$events , 'count'=>$count]);
    }

    /**dd
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(EventRequest $request)
    {
        try
        {
            $events= new Events;

            $events->church_id      = Auth::user()->church_id;
            $events->select_type    = $request->select_type;
            $events->title          = $request->title;
            $events->description    = $request->description;
            $events->repeats        = $request->repeats;
            $events->freq           = $request->freq;
            $events->freq_term      = $request->freq_term;
            $events->location       = $request->location;
            $events->category       = $request->category;
            $events->organised_by   = $request->organised_by;
            $events->start_date     = date('Y-m-d H:i:s',strtotime($request->start_date));
            $events->end_date       = date('Y-m-d H:i:s',strtotime($request->end_date));

            if ($request->cover_image_id) {
                if (str_starts_with($request->cover_image_id, 'media_')) {
                    $mediaId    = str_replace('media_', '', $request->cover_image_id);
                    $mediaImage = \App\Models\MediaFile::where([
                        ['id', $mediaId],
                        ['church_id', Auth::user()->church_id],
                        ['media_type', 'image'],
                    ])->first();
                    if ($mediaImage) {
                        $events->image = $mediaImage->url;
                    }
                } elseif ($request->cover_image_path) {
                    $events->image = $request->cover_image_path;
                }
            }

            $events->save();

            $executed_at  =  date('Y-m-d', strtotime('-2 days', strtotime($events->start_date)));
            $this->sendToReminderEvent($events,$executed_at,'first');

            if(env('MAIL_STATUS') === 'on')
            {
                event(new CalendarEvent($events));
            }

            $data=[];

            $data['church_id']=Auth::user()->church_id;
            $data['message']='New Event created';
            $data['type']='event';

            event(new PushEvent($data));

            $array=[];

            $array['church_id']=Auth::user()->church_id;
            $array['details']='New Event created';

            event(new PushNotificationEvent($array));

            $message='Events Added Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $events,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_EVENT,
                $message
            );
            $res['success']=$message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    /**
     * @param $id
     */
    public function edit($id)
    {
        $event = Events::where('id',$id)->get();
        $event = EditEventResource::collection($event);

        return $event;
    }

    public function validateedit(EventUpdateRequest $request)
    {
        //
    }

    /**
     * @param Request $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try
        {
            $events = Events::where('id',$id)->first();

            if($request->file('image'))
            {
                $file = $request->file('image');
                $path = $this->uploadFile('uploads/admin/event/image',$file);
                $events->image = $path;
            }
            else
            {
                $events->image = $events->image;
            }

            $events->title       = $request->title;
            $events->description = $request->description;
            $events->repeats     = $request->repeats;
            $events->freq        = $request->freq;
            $events->freq_term   = $request->freq_term;
            $events->location    = $request->location;
            $events->category    = $request->category;
            $events->organised_by= $request->organised_by;
            $events->start_date  = date('Y-m-d H:i:s',strtotime($request->start_date));
            $events->end_date    = date('Y-m-d H:i:s',strtotime($request->end_date));

            if ($request->cover_image_id) {
                if (str_starts_with($request->cover_image_id, 'media_')) {
                    $mediaId    = str_replace('media_', '', $request->cover_image_id);
                    $mediaImage = \App\Models\MediaFile::where([
                        ['id', $mediaId],
                        ['church_id', Auth::user()->church_id],
                        ['media_type', 'image'],
                    ])->first();
                    if ($mediaImage) {
                        $events->image = $mediaImage->url;
                    }
                } elseif ($request->cover_image_path) {
                    $events->image = $request->cover_image_path;
                }
            }

            $events->save();


            $data=[];

            $data['church_id']=Auth::user()->church_id;
            $data['message']='Event updated';
            $data['type']='event';

            event(new PushEvent($data));

            $message=('Events Updated Successfully');
            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $events,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_EVENT,
                $message
            );

            $res['success']=$message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }


    public function changeevent(Request $request, $id)
    {
        try
        {
            $event = Events::findOrFail($id);

            if ($request->end_date === 'undefined')
                $request['end_date'] = date('Y-m-d H:i:s', strtotime($request->start_date));

            if($request->start_date === $request->end_date)
                $request['allDay']=1;

            $event->fill($request->all());
            $event->save();
            echo json_encode(['status' => 'Event has been update']);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }


    public function destroy($id)
    {
        try
        {
            $event = Events::where('id',$id)->first();
            $event->delete();

            $message=('Events Deleted Successfully');

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $event,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_EVENT,
                $message
            );

            return redirect('/admin/events')->with(['message' => 'Event deleted']);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    /**
     * @param $facility
     * @param $asset
     * @return string
     */
    public function events()
    {

        $events = Events::where('church_id',Auth::user()->church_id)->get();

        $items = array();

        foreach ($events as $event) {

            if ($event->repeats === 1) {
                //create multiple entries for repeating events
                //count days from start to end and repeat
                if ($event->freq_term === 'day') {
                    foreach ($this->getDailyTasks($event) as $s) {
                        array_push($items, $s);
                    }
                }

                if ($event->freq_term === 'week') {
                    foreach ($this->getWeeklyTasks($event) as $s) {
                        array_push($items, $s);
                    }
                }

                if ($event->freq_term === 'month') {
                    foreach ($this->getMonthlyTasks($event) as $s) {
                        array_push($items, $s);
                    }
                }
                if ($event->freq_term === 'year') {
                    foreach ($this->getYearlyTasks($event) as $s) {
                        array_push($items, $s);
                    }
                }

            } else {
                foreach ($this->getDayTask($event) as $s) {
                    array_push($items, $s);
                }
            }
        }

        return json_encode($items);
    }

    public function show($id)
    {
        $event = Events::where('id',$id)->first();
        if (!$event) {
            abort(404);
        }
        if(Gate::allows('event',$event))
        {
            if(date('Y-m-d H:i:s',strtotime($event->start_date)) <= date('Y-m-d H:i:s'))
            {
                $expired = true;
            }
            else
            {
                $expired = false;
            }
            return view('admin.events.show',['event'=>$event , 'expired' => $expired]);
        }
        else
        {
            abort(403);
        }
    }

    public function showdetails($id)
    {
        $event = Events::where([['id',$id],['church_id',Auth::user()->church_id]])->get();
        $event = ShowEventResource::collection($event);

        return $event;
    }

    public function showimage($event_id)
    {
        $event = EventGallery::where([['event_id',$event_id],['church_id',Auth::user()->church_id]])->get();
        $event = ShowEventGalleryResource::collection($event);

        return $event;
    }

    public function details($id)
    {
        $events=Events::where('id',$id)->first();
        if(Gate::allows('event',$events))
        {
            $array=[];

            $array['id']=$events->id;
            $array['title']=$events->title;
            $array['description']=$events->description;
            $array['repeats']=$events->repeats;
            if($array['repeats']==='yes')
            {
                $array['freq']=$events->freq;
                $array['freq_term']=$events->freq_term;
            }
            $array['location']=$events->location;
            $array['category']=$events->category;
            $array['organised_by']=$events->organised_by;
            $array['image']=$events->ImagePath;
            $array['start_date']=date('d-F-Y',strtotime($events->start_date));
            $array['end_date']=$events->end_date;

            return $array;
        }
        else
        {
            abort(403);
        }
    }

    public function showAttendees($id,$status)
    {
        if($status === 'not_attended')
        {
            $is_present = 0;
        }
        else
        {
            $is_present = 1;
        }
        $event = Events::where('id',$id)->first();
        $attendance = Attendance::where([
            ['church_id',$event->church_id],
            ['title',$event->title],
            ['category',$event->category],
            ['date',date('Y-m-d H:i:s',strtotime($event->start_date))],
            ['is_present',$is_present]
        ])->get();

        $attendance = AttendanceResource::collection($attendance);

        return $attendance;
    }
}
