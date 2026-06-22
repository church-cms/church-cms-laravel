<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ShowEventGallery as ShowEventGalleryResource;
use App\Http\Resources\Attendance as AttendanceResource;
use App\Http\Resources\EditEvent as EditEventResource;
use App\Http\Resources\ShowEvent as ShowEventResource;
use App\Events\Notification\PushNotificationEvent;
use App\Http\Requests\EventCreateRequest;
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
use Carbon\Carbon;

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
        $filter   = request('filter', 'all');
        $category = request('category', '');
        $now      = now();

        $query = Events::where('church_id', Auth::user()->church_id);

        if ($filter === 'upcoming') {
            $query->where('start_date', '>=', $now)->orderBy('start_date', 'desc');
        } elseif ($filter === 'completed') {
            $query->where('end_date', '<', $now)->orderBy('start_date', 'desc');
        } else {
            $query->orderBy('start_date', 'desc');
        }

        if ($category !== '') {
            $query->where('category', $category);
        }

        $events = $query->paginate(15)->withQueryString();
        $count  = $events->total();

        return view('admin.events.index', compact('events', 'count', 'filter', 'category'));
    }

    public function editForm($id)
    {
        $event = Events::where('id', $id)->first();
        if (!$event) abort(404);
        if (!Gate::allows('event', $event)) abort(403);

        $categories = [
            'culturals' => 'Culturals',
            'education' => 'Education',
            'meeting'   => 'Meeting',
            'prayer'    => 'Prayer',
            'sermon'    => 'Sermon',
        ];

        $eventDate       = date('Y-m-d', strtotime($event->start_date));
        $startTime       = date('H:i',   strtotime($event->start_date));
        $durationMinutes = $event->duration_minutes;
        if (!$durationMinutes && $event->repeats != 1) {
            $diff = strtotime($event->end_date) - strtotime($event->start_date);
            $durationMinutes = max(0, (int) ($diff / 60));
        }
        $seriesEndDate = $event->repeats == 1 ? date('Y-m-d', strtotime($event->end_date)) : null;

        $groups = \App\Models\Group::where('church_id', Auth::user()->church_id)
            ->orderBy('name')->get();

        return view('admin.events.edit', compact(
            'event',
            'categories',
            'eventDate',
            'startTime',
            'durationMinutes',
            'seriesEndDate',
            'groups'
        ));
    }

    public function storeEdit(Request $request, $id)
    {
        $event = Events::where('id', $id)->first();
        if (!$event) abort(404);
        if (!Gate::allows('event', $event)) abort(403);

        $rules = [
            'select_type'  => 'required|in:public,private,online',
            'schedule'     => 'required|in:0,1',
            'title'        => 'required|max:100',
            'description'  => 'required|max:255',
            'category'     => 'required',
            'organised_by' => 'required',
            'location'     => 'required',
            'event_date'   => 'required|date',
            'start_time'   => 'required',
            'duration'     => 'required',
        ];
        if ($request->schedule === '1') {
            $rules['freq']            = 'required|integer|min:1';
            $rules['freq_term']       = 'required|in:day,week,month,year';
            $rules['series_end_date'] = 'required|date';
        }
        if ($request->schedule === '1' && $request->freq_term === 'week') {
            $rules['days_of_week']   = 'required|array|min:1';
            $rules['days_of_week.*'] = 'integer|between:0,6';
        }
        $request->validate($rules);

        try {
            $startDateTime = $request->event_date . ' ' . $request->start_time . ':00';
            if ($request->schedule === '1') {
                $endDateTime = $request->series_end_date . ' 23:59:59';
            } else {
                $endDateTime = date('Y-m-d H:i:s', strtotime($startDateTime) + ((int) $request->duration * 60));
            }

            $event->select_type       = $request->select_type;
            $event->title             = $request->title;
            $event->description       = $request->description;
            $event->repeats           = $request->schedule;
            $event->freq              = $request->schedule === '1' ? $request->freq : null;
            $event->freq_term         = $request->schedule === '1' ? $request->freq_term : null;
            $event->days_of_week      = ($request->schedule === '1' && $request->freq_term === 'week')
                ? array_map('intval', $request->input('days_of_week', []))
                : null;
            $event->duration_minutes  = (int) $request->duration;
            $event->location          = $request->location;
            $event->category          = $request->category;
            $event->organised_by      = $request->organised_by;
            $event->start_date        = $startDateTime;
            $event->end_date          = $endDateTime;
            $event->publish_to_web       = $request->boolean('publish_to_web', false);
            $event->enable_gallery       = $request->boolean('enable_gallery', false);
            $event->enable_attendance    = $request->boolean('enable_attendance', false);
            $event->attendance_scope     = $event->enable_attendance ? ($request->input('attendance_scope', 'all')) : 'all';
            $event->attendance_group_id  = ($event->enable_attendance && $event->attendance_scope === 'group')
                ? $request->input('attendance_group_id') : null;

            if ($request->cover_image_id && str_starts_with($request->cover_image_id, 'media_')) {
                $mediaId    = str_replace('media_', '', $request->cover_image_id);
                $mediaImage = \App\Models\MediaFile::where([
                    ['id', $mediaId],
                    ['church_id', Auth::user()->church_id],
                    ['media_type', 'image'],
                ])->first();
                if ($mediaImage) $event->image = $mediaImage->url;
            } elseif ($request->cover_image_path) {
                $event->image = $request->cover_image_path;
            }

            $event->save();

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $event,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_EVENT,
                'Event Updated: ' . $event->title
            );

            return redirect()->route('admin.events.show', $event->id)
                ->with('successmessage', 'Event "' . $event->title . '" updated successfully.');
        } catch (Exception $e) {
            Log::error('EventsController@storeEdit: ' . $e->getMessage());
            return back()->withInput()->with('failmessage', 'Could not update event. Please try again.');
        }
    }

    public function newForm()
    {
        $categories = [
            'Culturals'  => 'Culturals',
            'Education'  => 'Education',
            'Meeting'    => 'Meeting',
            'prayer'     => 'Prayer',
            'sermon'     => 'Sermon',
        ];

        $groups = \App\Models\Group::where('church_id', Auth::user()->church_id)
            ->orderBy('name')->get();

        return view('admin.events.new', compact('categories', 'groups'));
    }

    public function storeNewcc(EventCreateRequest $request)
    {

        try {
            $startDateTime = $request->event_date . ' ' . $request->start_time . ':00';

            if ($request->schedule === '1') {
                // Recurring: end_date = series end date at end of day
                $endDateTime = $request->series_end_date . ' 23:59:59';
            } else {
                // One-time: end_date = start + duration minutes
                $endDateTime = date('Y-m-d H:i:s', strtotime($startDateTime) + ((int) $request->duration * 60));
            }

            $event = new Events;
            $event->church_id         = Auth::user()->church_id;
            $event->select_type       = $request->select_type;
            $event->title             = $request->title;
            $event->description       = $request->description;
            $event->repeats           = $request->schedule;
            $event->freq              = $request->schedule === '1' ? $request->freq : null;
            $event->freq_term         = $request->schedule === '1' ? $request->freq_term : null;
            $event->days_of_week      = ($request->schedule === '1' && $request->freq_term === 'week')
                ? array_map('intval', $request->input('days_of_week', []))
                : null;
            $event->duration_minutes  = (int) $request->duration;
            $event->location          = $request->location;
            $event->category          = $request->category;
            $event->organised_by      = $request->organised_by;
            $event->start_date        = $startDateTime;
            $event->end_date          = $endDateTime;
            $event->publish_to_web    = $request->boolean('publish_to_web', true);
            $event->enable_gallery    = $request->boolean('enable_gallery', true);
            $event->enable_attendance    = $request->boolean('enable_attendance', false);
            $event->attendance_scope     = $event->enable_attendance ? ($request->input('attendance_scope', 'all')) : 'all';
            $event->attendance_group_id  = ($event->enable_attendance && $event->attendance_scope === 'group')
                ? $request->input('attendance_group_id') : null;
            $event->created_by           = Auth::id();

            if ($request->cover_image_id && str_starts_with($request->cover_image_id, 'media_')) {
                $mediaId    = str_replace('media_', '', $request->cover_image_id);
                $mediaImage = \App\Models\MediaFile::where([
                    ['id', $mediaId],
                    ['church_id', Auth::user()->church_id],
                    ['media_type', 'image'],
                ])->first();
                if ($mediaImage) $event->image = $mediaImage->url;
            } elseif ($request->cover_image_path) {
                $event->image = $request->cover_image_path;
            }

            $event->save();

            $reminderDate = date('Y-m-d', strtotime('-2 days', strtotime($event->start_date)));
            $this->sendToReminderEvent($event, $reminderDate, 'first');

            if (env('MAIL_STATUS') === 'on') {
                event(new CalendarEvent($event));
            }

            $data = ['church_id' => Auth::user()->church_id, 'message' => 'New Event created', 'type' => 'event'];
            event(new PushEvent($data));

            $array = ['church_id' => Auth::user()->church_id, 'details' => 'New Event created'];
            event(new PushNotificationEvent($array));

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $event,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_EVENT,
                'Event Added: ' . $event->title
            );

            return redirect()->route('admin.events.index')
                ->with('successmessage', 'Event "' . $event->title . '" created successfully.');
        } catch (Exception $e) {
            Log::error('EventsController@storeNew: ' . $e->getMessage());


            return back()->withInput()->with('failmessage', 'Could not save event. Please try again.');
        }
    }




    public function storeNew(EventCreateRequest $request)
    {
        try {

            // =====================================================
            // ONE TIME EVENT
            // =====================================================

            if ($request->schedule === '0') {

                $startDateTime = $request->event_date . ' ' . $request->start_time . ':00';

                $endDateTime = date(
                    'Y-m-d H:i:s',
                    strtotime($startDateTime) + ((int) $request->duration * 60)
                );

                $event = new Events;

                $event->church_id         = Auth::user()->church_id;
                $event->select_type       = $request->select_type;
                $event->title             = $request->title;
                $event->description       = $request->description;

                $event->repeats           = 0;
                $event->freq              = null;
                $event->freq_term         = null;
                $event->month_type        = null;
                $event->days_of_week      = null;

                $event->duration_minutes  = (int) $request->duration;
                $event->location          = $request->location;
                $event->category          = $request->category;
                $event->organised_by      = $request->organised_by;

                $event->start_date        = $startDateTime;
                $event->end_date          = $endDateTime;

                $event->publish_to_web    = $request->boolean('publish_to_web', true);
                $event->enable_gallery    = $request->boolean('enable_gallery', true);

                $event->enable_attendance = $request->boolean('enable_attendance', false);

                $event->attendance_scope = $event->enable_attendance
                    ? $request->input('attendance_scope', 'all')
                    : 'all';

                $event->attendance_group_id =
                    ($event->enable_attendance && $event->attendance_scope === 'group')
                    ? $request->input('attendance_group_id')
                    : null;

                $event->created_by = Auth::id();

                // IMAGE
                if (
                    $request->cover_image_id
                    &&
                    str_starts_with($request->cover_image_id, 'media_')
                ) {

                    $mediaId = str_replace(
                        'media_',
                        '',
                        $request->cover_image_id
                    );

                    $mediaImage = \App\Models\MediaFile::where([
                        ['id', $mediaId],
                        ['church_id', Auth::user()->church_id],
                        ['media_type', 'image'],
                    ])->first();

                    if ($mediaImage) {
                        $event->image = $mediaImage->url;
                    }
                } elseif ($request->cover_image_path) {

                    $event->image = $request->cover_image_path;
                }

                $event->save();

                // REMINDER
                $reminderDate = date(
                    'Y-m-d',
                    strtotime('-2 days', strtotime($event->start_date))
                );

                $this->sendToReminderEvent(
                    $event,
                    $reminderDate,
                    'first'
                );

                // MAIL
                if (env('MAIL_STATUS') === 'on') {
                    event(new CalendarEvent($event));
                }

                // PUSH
                $data = [
                    'church_id' => Auth::user()->church_id,
                    'message'   => 'New Event created',
                    'type'      => 'event'
                ];

                event(new PushEvent($data));

                $array = [
                    'church_id' => Auth::user()->church_id,
                    'details'   => 'New Event created',
                    'message_type' => 'event',
                    'message_id' => $event->id
                ];

                event(new PushNotificationEvent($array));

                // LOG
                $ip = $this->getRequestIP();

                $this->doActivityLog(
                    $event,
                    Auth::user(),
                    [
                        'ip'      => $ip,
                        'details' => $_SERVER['HTTP_USER_AGENT']
                    ],
                    LOGNAME_ADD_EVENT,
                    'Event Added: ' . $event->title
                );
            }

            // =====================================================
            // RECURRING EVENTS
            // =====================================================

            else {

                $start = Carbon::parse($request->event_date);

                $seriesEnd = Carbon::parse(
                    $request->series_end_date
                );

                $current = $start->copy();

                while ($current->lte($seriesEnd)) {

                    $eventDate = null;

                    // ============================================
                    // DAILY
                    // ============================================

                    if ($request->freq_term == 'day') {

                        $eventDate = $current->copy();

                        $current->addDays($request->freq);
                    }

                    // ============================================
                    // WEEKLY
                    // ============================================

                    elseif ($request->freq_term == 'week') {

                        $weekStart = $current->copy()->startOfWeek();

                        foreach ($request->days_of_week as $dayNum) {

                            $date = $weekStart
                                ->copy()
                                ->addDays($dayNum);

                            if (
                                $date->gte($start)
                                &&
                                $date->lte($seriesEnd)
                            ) {

                                $this->saveRecurringEvent(
                                    $request,
                                    $date
                                );
                            }
                        }

                        $current->addWeeks($request->freq);

                        continue;
                    }

                    // ============================================
                    // MONTHLY
                    // ============================================

                    elseif ($request->freq_term == 'month') {

                        // day_21

                        if (
                            str_starts_with(
                                $request->month_type,
                                'day_'
                            )
                        ) {

                            $day = (int) str_replace(
                                'day_',
                                '',
                                $request->month_type
                            );

                            $eventDate = $current
                                ->copy()
                                ->day($day);
                        }

                        // week_3_4

                        elseif (
                            str_starts_with(
                                $request->month_type,
                                'week_'
                            )
                        ) {

                            $parts = explode(
                                '_',
                                $request->month_type
                            );

                            $weekNumber = (int) $parts[1];

                            $weekDay = (int) $parts[2];

                            $eventDate = $current
                                ->copy()
                                ->nthOfMonth(
                                    $weekNumber,
                                    $weekDay
                                );
                        }

                        $current->addMonths($request->freq);
                    }

                    // ============================================
                    // YEARLY
                    // ============================================

                    elseif ($request->freq_term == 'year') {

                        $eventDate = $current->copy();

                        $current->addYears($request->freq);
                    }

                    // ============================================
                    // SAVE EVENT
                    // ============================================

                    if (
                        $eventDate
                        &&
                        $eventDate->lte($seriesEnd)
                    ) {

                        $this->saveRecurringEvent(
                            $request,
                            $eventDate
                        );
                    }
                }
            }

            return redirect()
                ->route('admin.events.index')
                ->with(
                    'successmessage',
                    'Event created successfully.'
                );
        } catch (Exception $e) {

            Log::error(
                'EventsController@storeNew: '
                    . $e->getMessage()
            );

            return back()
                ->withInput()
                ->with(
                    'failmessage',
                    'Could not save event. Please try again.'
                );
        }
    }

    private function saveRecurringEvent($request, $eventDate)
    {
        $startDateTime = $eventDate->format('Y-m-d')
            . ' '
            . $request->start_time
            . ':00';

        $endDateTime = Carbon::parse($startDateTime)
            ->addMinutes($request->duration);

        $event = new Events;

        $event->church_id         = Auth::user()->church_id;
        $event->select_type       = $request->select_type;
        $event->title             = $request->title;
        $event->description       = $request->description;

        $event->repeats           = 0;

        $event->freq              = null;
        $event->freq_term         = null;
        $event->month_type        = null;
        $event->days_of_week      = null;

        $event->duration_minutes  = (int) $request->duration;

        $event->location          = $request->location;
        $event->category          = $request->category;
        $event->organised_by      = $request->organised_by;

        $event->start_date        = $startDateTime;
        $event->end_date          = $endDateTime;

        $event->publish_to_web    = $request->boolean(
            'publish_to_web',
            true
        );

        $event->enable_gallery    = $request->boolean(
            'enable_gallery',
            true
        );

        $event->enable_attendance = $request->boolean(
            'enable_attendance',
            false
        );

        $event->attendance_scope = $event->enable_attendance
            ? $request->input('attendance_scope', 'all')
            : 'all';

        $event->attendance_group_id =
            ($event->enable_attendance
                && $event->attendance_scope === 'group')
            ? $request->input('attendance_group_id')
            : null;

        $event->created_by = Auth::id();

        // IMAGE
        if (
            $request->cover_image_id
            &&
            str_starts_with($request->cover_image_id, 'media_')
        ) {

            $mediaId = str_replace(
                'media_',
                '',
                $request->cover_image_id
            );

            $mediaImage = \App\Models\MediaFile::where([
                ['id', $mediaId],
                ['church_id', Auth::user()->church_id],
                ['media_type', 'image'],
            ])->first();

            if ($mediaImage) {
                $event->image = $mediaImage->url;
            }
        } elseif ($request->cover_image_path) {

            $event->image = $request->cover_image_path;
        }

        $event->save();
    }

    /**dd
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(EventRequest $request)
    {
        try {
            $events = new Events;

            $events->church_id      = Auth::user()->church_id;
            $events->select_type    = $request->select_type;
            $events->title          = $request->title;
            $events->description    = $request->description;
            $events->repeats         = $request->repeats;
            $events->freq            = $request->freq;
            $events->freq_term       = $request->freq_term;
            $events->days_of_week    = ($request->repeats == 1 && $request->freq_term === 'week')
                ? array_map('intval', $request->input('days_of_week', []))
                : null;
            $events->duration_minutes = $request->duration_minutes ? (int) $request->duration_minutes : null;
            $events->location        = $request->location;
            $events->category       = $request->category;
            $events->organised_by      = $request->organised_by;
            $events->publish_to_web    = $request->boolean('publish_to_web', true);
            $events->enable_gallery    = $request->boolean('enable_gallery', true);
            $events->enable_attendance = $request->boolean('enable_attendance', false);
            $events->start_date     = date('Y-m-d H:i:s', strtotime($request->start_date));
            $events->end_date       = date('Y-m-d H:i:s', strtotime($request->end_date));

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
            $this->sendToReminderEvent($events, $executed_at, 'first');

            if (env('MAIL_STATUS') === 'on') {
                event(new CalendarEvent($events));
            }

            $data = [];

            $data['church_id'] = Auth::user()->church_id;
            $data['message'] = 'New Event created';
            $data['type'] = 'event';

            event(new PushEvent($data));

            $array = [];

            $array['church_id'] = Auth::user()->church_id;
            $array['details'] = 'New Event created';

            event(new PushNotificationEvent($array));

            $message = 'Events Added Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $events,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_EVENT,
                $message
            );
            $res['success'] = $message;
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * @param $id
     */
    public function edit($id)
    {
        $event = Events::where('id', $id)->get();
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
        try {
            $events = Events::where('id', $id)->first();

            if ($request->file('image')) {
                $file = $request->file('image');
                $path = $this->uploadFile('uploads/admin/event/image', $file);
                $events->image = $path;
            } else {
                $events->image = $events->image;
            }

            $events->title          = $request->title;
            $events->description    = $request->description;
            $events->repeats        = $request->repeats;
            $events->freq           = $request->freq;
            $events->freq_term      = $request->freq_term;
            $events->days_of_week   = ($request->repeats == 1 && $request->freq_term === 'week')
                ? array_map('intval', $request->input('days_of_week', []))
                : null;
            $events->duration_minutes = $request->duration_minutes ? (int) $request->duration_minutes : null;
            $events->location       = $request->location;
            $events->category    = $request->category;
            $events->organised_by      = $request->organised_by;
            $events->publish_to_web    = $request->boolean('publish_to_web', true);
            $events->enable_gallery    = $request->boolean('enable_gallery', true);
            $events->enable_attendance = $request->boolean('enable_attendance', false);
            $events->start_date  = date('Y-m-d H:i:s', strtotime($request->start_date));
            $events->end_date    = date('Y-m-d H:i:s', strtotime($request->end_date));

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


            $data = [];

            $data['church_id'] = Auth::user()->church_id;
            $data['message'] = 'Event updated';
            $data['type'] = 'event';

            event(new PushEvent($data));

            $message = ('Events Updated Successfully');
            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $events,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_EVENT,
                $message
            );

            $res['success'] = $message;
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }


    public function changeevent(Request $request, $id)
    {
        try {
            $event = Events::findOrFail($id);

            if ($request->end_date === 'undefined')
                $request['end_date'] = date('Y-m-d H:i:s', strtotime($request->start_date));

            if ($request->start_date === $request->end_date)
                $request['allDay'] = 1;

            $event->fill($request->all());
            $event->save();
            echo json_encode(['status' => 'Event has been update']);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $event = Events::where('id', $id)->first();
            $event->delete();

            $message = ('Events Deleted Successfully');

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $event,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_EVENT,
                $message
            );

            return redirect('/admin/events')->with(['message' => 'Event deleted']);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function events()
    {
        $events = Events::where('church_id', Auth::user()->church_id)->get();
        $items  = [];

        foreach ($events as $event) {
            if ($event->repeats == 1) {
                if ($event->freq_term === 'day')   $items = array_merge($items, $this->getDailyTasks($event));
                if ($event->freq_term === 'week')  $items = array_merge($items, $this->getWeeklyTasks($event));
                if ($event->freq_term === 'month') $items = array_merge($items, $this->getMonthlyTasks($event));
                if ($event->freq_term === 'year')  $items = array_merge($items, $this->getYearlyTasks($event));
            } else {
                $items = array_merge($items, $this->getDayTask($event));
            }
        }

        return response()->json($items);
    }

    private function buildCalendarItem(Events $event, \DateTime $start, \DateTime $end): array
    {
        return [
            'id'    => $event->id,
            'title' => $event->title,
            'start' => $start->format('Y-m-d\TH:i:s'),
            'end'   => $end->format('Y-m-d\TH:i:s'),
            'allDay' => (bool) $event->allDay,
        ];
    }

    private function getDayTask(Events $event): array
    {
        return [$this->buildCalendarItem(
            $event,
            new \DateTime($event->start_date),
            new \DateTime($event->end_date)
        )];
    }

    private function getDailyTasks(Events $event): array
    {
        $items    = [];
        $freq     = max(1, (int) $event->freq);
        $duration = max(1, (int) ($event->duration_minutes ?? 60));
        $cursor   = new \DateTime($event->start_date);
        $seriesEnd = new \DateTime($event->end_date);

        while ($cursor <= $seriesEnd) {
            $occEnd = (clone $cursor)->modify('+' . $duration . ' minutes');
            $items[] = $this->buildCalendarItem($event, $cursor, $occEnd);
            $cursor->modify('+' . $freq . ' days');
        }

        return $items;
    }

    private function getWeeklyTasks(Events $event): array
    {
        $items      = [];
        $freq       = max(1, (int) $event->freq);
        $duration   = max(1, (int) ($event->duration_minutes ?? 60));
        $daysOfWeek = is_array($event->days_of_week) ? array_map('intval', $event->days_of_week) : [];

        $startDt   = new \DateTime($event->start_date);
        $seriesEnd = new \DateTime($event->end_date);

        if (empty($daysOfWeek)) {
            $daysOfWeek = [(int) $startDt->format('w')];
        }

        // Anchor to the Sunday of the week containing start_date
        $anchor = clone $startDt;
        $anchor->setTime(0, 0, 0);
        $anchor->modify('-' . (int) $anchor->format('w') . ' days');

        $weekIndex = 0;

        while (true) {
            $weekSunday = (clone $anchor)->modify('+' . ($weekIndex * 7) . ' days');
            if ($weekSunday > $seriesEnd) break;

            foreach ($daysOfWeek as $dayNum) {
                $occ = (clone $weekSunday)->modify('+' . $dayNum . ' days');
                $occ->setTime((int) $startDt->format('H'), (int) $startDt->format('i'), 0);

                if ($occ < $startDt || $occ > $seriesEnd) continue;

                $occEnd = (clone $occ)->modify('+' . $duration . ' minutes');
                $items[] = $this->buildCalendarItem($event, $occ, $occEnd);
            }

            $weekIndex += $freq;
        }

        return $items;
    }

    private function getMonthlyTasks(Events $event): array
    {
        $items    = [];
        $freq     = max(1, (int) $event->freq);
        $duration = max(1, (int) ($event->duration_minutes ?? 60));
        $cursor   = new \DateTime($event->start_date);
        $seriesEnd = new \DateTime($event->end_date);

        while ($cursor <= $seriesEnd) {
            $occEnd = (clone $cursor)->modify('+' . $duration . ' minutes');
            $items[] = $this->buildCalendarItem($event, $cursor, $occEnd);
            $cursor->modify('+' . $freq . ' months');
        }

        return $items;
    }

    private function getYearlyTasks(Events $event): array
    {
        $items    = [];
        $freq     = max(1, (int) $event->freq);
        $duration = max(1, (int) ($event->duration_minutes ?? 60));
        $cursor   = new \DateTime($event->start_date);
        $seriesEnd = new \DateTime($event->end_date);

        while ($cursor <= $seriesEnd) {
            $occEnd = (clone $cursor)->modify('+' . $duration . ' minutes');
            $items[] = $this->buildCalendarItem($event, $cursor, $occEnd);
            $cursor->modify('+' . $freq . ' years');
        }

        return $items;
    }

    public function show($id)
    {
        $event = Events::where('id', $id)->first();
        if (!$event) abort(404);

        if (Gate::allows('event', $event)) {
            $expired = date('Y-m-d H:i:s', strtotime($event->start_date)) <= date('Y-m-d H:i:s');

            $photos = EventGallery::where([
                ['event_id', $id],
                ['church_id', Auth::user()->church_id],
            ])->orderBy('created_at', 'desc')->get();

            $notes = \App\Models\Notes::where([
                ['entity_id', $id],
                ['entity_name', 'event'],
            ])->orderBy('created_at', 'desc')->get();

            $attended    = collect();
            $notAttended = collect();
            if ($expired) {
                $base = [
                    ['church_id', $event->church_id],
                    ['title',     $event->title],
                    ['category',  $event->category],
                    ['date',      date('Y-m-d H:i:s', strtotime($event->start_date))],
                ];
                $attended    = Attendance::where(array_merge($base, [['is_present', 1]]))->get();
                $notAttended = Attendance::where(array_merge($base, [['is_present', 0]]))->get();
            }

            $sessions = collect();
            if ($event->enable_attendance) {
                $sessions = \App\Models\EventAttendanceSession::where('event_id', $id)
                    ->withCount('attendees')
                    ->orderByDesc('attendance_date')
                    ->get();
            }

            return view('admin.events.show', compact('event', 'expired', 'photos', 'notes', 'attended', 'notAttended', 'sessions'));
        }

        abort(403);
    }

    public function showdetails($id)
    {
        $event = Events::where([['id', $id], ['church_id', Auth::user()->church_id]])->get();
        $event = ShowEventResource::collection($event);

        return $event;
    }

    public function showimage($event_id)
    {
        $event = EventGallery::where([['event_id', $event_id], ['church_id', Auth::user()->church_id]])->get();
        $event = ShowEventGalleryResource::collection($event);

        return $event;
    }

    public function details($id)
    {
        $events = Events::where('id', $id)->first();
        if (Gate::allows('event', $events)) {
            $array = [];

            $array['id'] = $events->id;
            $array['title'] = $events->title;
            $array['description'] = $events->description;
            $array['repeats'] = $events->repeats;
            if ($array['repeats'] === 'yes') {
                $array['freq'] = $events->freq;
                $array['freq_term'] = $events->freq_term;
            }
            $array['location'] = $events->location;
            $array['category'] = $events->category;
            $array['organised_by'] = $events->organised_by;
            $array['image'] = $events->ImagePath;
            $array['start_date'] = date('d-F-Y', strtotime($events->start_date));
            $array['end_date'] = $events->end_date;

            return $array;
        } else {
            abort(403);
        }
    }

    public function showAttendees($id, $status)
    {
        if ($status === 'not_attended') {
            $is_present = 0;
        } else {
            $is_present = 1;
        }
        $event = Events::where('id', $id)->first();
        $attendance = Attendance::where([
            ['church_id', $event->church_id],
            ['title', $event->title],
            ['category', $event->category],
            ['date', date('Y-m-d H:i:s', strtotime($event->start_date))],
            ['is_present', $is_present]
        ])->get();

        $attendance = AttendanceResource::collection($attendance);

        return $attendance;
    }
}
