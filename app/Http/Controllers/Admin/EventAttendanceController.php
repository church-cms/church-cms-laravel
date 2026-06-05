<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventAttendanceSession;
use App\Models\EventAttendee;
use App\Models\EventManager;
use App\Models\Events;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;
use Exception;
use Log;

class EventAttendanceController extends Controller
{
    use LogActivity;
    use Common;

    public function sessions($event_id)
    {
        $event = Events::where([['church_id', Auth::user()->church_id], ['id', $event_id]])->firstOrFail();
        $sessions = EventAttendanceSession::where('event_id', $event_id)
            ->with(['openedBy', 'lockedBy'])
            ->withCount('attendees')
            ->orderByDesc('attendance_date')
            ->get();

        return view('admin.attendance.sessions', compact('event', 'sessions'));
    }

    public function openSession(Request $request, $event_id)
    {
        $event = Events::where([['church_id', Auth::user()->church_id], ['id', $event_id]])->firstOrFail();

        $date = $request->input('attendance_date', now()->toDateString());

        try {
            $session = EventAttendanceSession::firstOrCreate(
                ['event_id' => $event_id, 'attendance_date' => $date],
                [
                    'church_id' => Auth::user()->church_id,
                    'opened_by' => Auth::id(),
                ]
            );

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                Auth::user(),
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'], 'event' => $event->title, 'date' => $date],
                LOGNAME_OPEN_ATTENDANCE_SESSION,
                'Opened attendance session for ' . $event->title . ' on ' . $date
            );

            return redirect()->route('admin.attendance.session', $session->id)
                ->with('successmessage', 'Attendance session opened.');
        } catch (Exception $e) {
            Log::error('EventAttendanceController@openSession: ' . $e->getMessage());
            return back()->with('failmessage', 'Could not open session.');
        }
    }

    public function showSession($session_id)
    {

        $session = EventAttendanceSession::with(['event', 'openedBy', 'lockedBy'])->findOrFail($session_id);

        abort_unless($session->church_id === Auth::user()->church_id, 403);



        $attendees = EventAttendee::where('session_id', $session_id)
            ->with(['member.userprofile', 'scannedBy'])
            ->orderBy('scanned_at')
            ->get();

        //dd($attendees);

        return view('admin.attendance.session', compact('session', 'attendees'));
    }

    public function lock($session_id)
    {
        $session = EventAttendanceSession::findOrFail($session_id);
        abort_unless($session->church_id === Auth::user()->church_id, 403);

        $session->update(['locked_at' => now(), 'locked_by' => Auth::id()]);

        $ip = $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'], 'session_id' => $session_id],
            LOGNAME_LOCK_ATTENDANCE_SESSION,
            'Locked attendance session #' . $session_id
        );

        return back()->with('successmessage', 'Session locked. No further check-ins allowed.');
    }

    public function unlock($session_id)
    {
        $session = EventAttendanceSession::findOrFail($session_id);
        abort_unless($session->church_id === Auth::user()->church_id, 403);

        $session->update(['locked_at' => null, 'locked_by' => null]);

        $ip = $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'], 'session_id' => $session_id],
            LOGNAME_UNLOCK_ATTENDANCE_SESSION,
            'Unlocked attendance session #' . $session_id
        );

        return back()->with('successmessage', 'Session unlocked.');
    }

    public function export($session_id)
    {
        $session = EventAttendanceSession::with('event')->findOrFail($session_id);
        abort_unless($session->church_id === Auth::user()->church_id, 403);

        $attendees = EventAttendee::where('session_id', $session_id)
            ->with(['member.userprofile', 'scannedBy'])
            ->orderBy('scanned_at')
            ->get();

        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(['Name', 'Mobile', 'Email', 'Checked In At', 'Scanned By']);

        foreach ($attendees as $attendee) {
            $csv->insertOne([
                $attendee->member->userprofile->firstname . ' ' . $attendee->member->userprofile->lastname,
                $attendee->member->mobile_no,
                $attendee->member->email,
                $attendee->scanned_at ? $attendee->scanned_at->format('Y-m-d H:i:s') : '',
                $attendee->scannedBy ? $attendee->scannedBy->name : '',
            ]);
        }

        $ip = $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'], 'session_id' => $session_id],
            LOGNAME_EXPORT_ATTENDANCE,
            'Exported attendance for session #' . $session_id
        );

        $filename = 'attendance_' . $session->event->title . '_' . $session->attendance_date->format('Y-m-d') . '.csv';
        $csv->output($filename);
    }

    public function manageManagers($event_id)
    {
        $event = Events::where([['church_id', Auth::user()->church_id], ['id', $event_id]])->firstOrFail();

        $assigned = EventManager::where('event_id', $event_id)->with('staff')->get();

        $subadmins = User::where('church_id', Auth::user()->church_id)
            ->where('usergroup_id', 4)
            ->whereNotIn('id', $assigned->pluck('user_id'))
            ->get();

        return view('admin.attendance.managers', compact('event', 'assigned', 'subadmins'));
    }

    public function storeManager(Request $request, $event_id)
    {
        $event = Events::where([['church_id', Auth::user()->church_id], ['id', $event_id]])->firstOrFail();

        $request->validate(['user_id' => 'required|exists:users,id']);

        try {
            EventManager::firstOrCreate(['event_id' => $event_id, 'user_id' => $request->user_id]);

            $staff = User::find($request->user_id);
            $ip = $this->getRequestIP();
            $this->doActivityLog(
                Auth::user(),
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'], 'staff' => $staff->name, 'event' => $event->title],
                LOGNAME_ASSIGN_EVENT_MANAGER,
                'Assigned ' . $staff->name . ' as manager for ' . $event->title
            );

            return back()->with('successmessage', $staff->name . ' assigned as event manager.');
        } catch (Exception $e) {
            Log::error('EventAttendanceController@storeManager: ' . $e->getMessage());
            return back()->with('failmessage', 'Could not assign manager.');
        }
    }

    public function removeManager($event_id, $user_id)
    {
        $event = Events::where([['church_id', Auth::user()->church_id], ['id', $event_id]])->firstOrFail();

        EventManager::where(['event_id' => $event_id, 'user_id' => $user_id])->delete();

        $staff = User::find($user_id);
        $ip = $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'], 'staff_id' => $user_id, 'event' => $event->title],
            LOGNAME_REMOVE_EVENT_MANAGER,
            'Removed staff #' . $user_id . ' as manager for ' . $event->title
        );

        return back()->with('successmessage', 'Manager removed.');
    }

    // ── Web check-in (Option C) ─────────────────────────────────────────

    public function checkin($session_id)
    {
        $session = EventAttendanceSession::with(['event', 'openedBy'])->findOrFail($session_id);
        abort_unless($session->church_id === Auth::user()->church_id, 403);

        $recentAttendees = EventAttendee::where('session_id', $session_id)
            ->with(['member.userprofile'])
            ->orderByDesc('scanned_at')
            ->get();

        $count = $recentAttendees->count();

        return view('admin.attendance.checkin', compact('session', 'recentAttendees', 'count'));
    }

    public function searchMember(Request $request, $session_id)
    {
        $session = EventAttendanceSession::findOrFail($session_id);
        abort_unless($session->church_id === Auth::user()->church_id, 403);

        $q = trim($request->input('q', ''));
        if (strlen($q) < 2) return response()->json([]);

        $checkedInIds = EventAttendee::where('session_id', $session_id)->pluck('user_id');

        $membersQuery = User::where('church_id', $session->church_id)
            ->where('usergroup_id', 5)
            ->where(function ($query) use ($q) {
                $query->where('name', 'LIKE', "%{$q}%")
                    ->orWhereHas('userprofile', function ($q2) use ($q) {
                        $q2->where('firstname', 'LIKE', "%{$q}%")
                            ->orWhere('lastname',  'LIKE', "%{$q}%");
                    });
            });

        // Restrict to group members if event has a specific group scope
        $event = $session->event;
        if ($event && $event->attendance_scope === 'group' && $event->attendance_group_id) {
            $groupUserIds = \App\Models\GroupLink::where('group_id', $event->attendance_group_id)
                ->pluck('user_id');
            $membersQuery->whereIn('id', $groupUserIds);
        }

        $members = $membersQuery->with('userprofile')
            ->take(10)
            ->get()
            ->map(function ($u) use ($checkedInIds) {
                $profile = $u->userprofile;
                return [
                    'id'         => $u->id,
                    'username'   => $u->name,
                    'full_name'  => $profile ? trim($profile->firstname . ' ' . $profile->lastname) : $u->name,
                    'avatar'     => $profile?->AvatarPath,
                    'checked_in' => $checkedInIds->contains($u->id),
                ];
            });

        return response()->json($members);
    }

    public function markAttendee(Request $request, $session_id)
    {
        $session = EventAttendanceSession::findOrFail($session_id);
        abort_unless($session->church_id === Auth::user()->church_id, 403);

        if ($session->locked_at) {
            return response()->json(['error' => 'Session is locked. No further check-ins allowed.'], 403);
        }

        $user = null;
        if ($request->filled('user_id')) {
            $user = User::where([['id', $request->user_id], ['church_id', $session->church_id]])->first();
        } elseif ($request->filled('username')) {
            $user = User::where([['name', $request->username], ['church_id', $session->church_id]])->first();
        }

        if (!$user) {
            return response()->json(['error' => 'Member not found.'], 404);
        }

        $existing = EventAttendee::where(['session_id' => $session_id, 'user_id' => $user->id])->first();
        if ($existing) {
            $profile = $user->userprofile;
            return response()->json([
                'already_checked_in' => true,
                'member' => [
                    'id'        => $user->id,
                    'username'  => $user->name,
                    'full_name' => $profile ? trim($profile->firstname . ' ' . $profile->lastname) : $user->name,
                    'avatar'    => $profile?->AvatarPath,
                    'scanned_at' => optional($existing->scanned_at)->format('h:i A'),
                ],
            ], 409);
        }

        $attendee = EventAttendee::create([
            'session_id' => $session_id,
            'church_id'  => $session->church_id,
            'event_id'   => $session->event_id,
            'user_id'    => $user->id,
            'scanned_at' => now(),
            'scanned_by' => Auth::id(),
        ]);

        $profile = $user->userprofile;
        return response()->json([
            'success' => true,
            'member'  => [
                'id'        => $user->id,
                'username'  => $user->name,
                'full_name' => $profile ? trim($profile->firstname . ' ' . $profile->lastname) : $user->name,
                'avatar'    => $profile?->AvatarPath,
                'scanned_at' => $attendee->scanned_at->format('h:i A'),
            ],
        ]);
    }

    public function removeAttendee($session_id, $user_id)
    {
        $session = EventAttendanceSession::findOrFail($session_id);
        abort_unless($session->church_id === Auth::user()->church_id, 403);

        if ($session->locked_at) {
            return response()->json(['error' => 'Session is locked.'], 403);
        }

        EventAttendee::where(['session_id' => $session_id, 'user_id' => $user_id])->delete();

        return response()->json(['success' => true]);
    }
}
