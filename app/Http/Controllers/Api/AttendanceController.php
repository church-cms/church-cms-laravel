<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventAttendanceSession;
use App\Models\EventAttendee;
use App\Models\EventManager;
use App\Models\Events;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Log;
use OpenApi\Attributes as OA;

class AttendanceController extends Controller
{
    /**
     * List events the authenticated staff member is assigned to,
     * where attendance tracking is enabled.
     */
    #[OA\Get(
        path: '/api/v1/attendance/events',
        summary: 'List events assigned to the authenticated staff member with attendance enabled',
        operationId: '0d2f80fb83d5c305051c47b3e28db0c5',
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/AttendanceEventsResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function myEvents()
    {
        $staffId = Auth::id();
        $churchId = Auth::user()->church_id;

        $assignedEventIds = EventManager::where('user_id', $staffId)
            ->pluck('event_id');

        $events = Events::where('church_id', $churchId)
            ->where('enable_attendance', true)
            ->whereIn('id', $assignedEventIds)
            ->get()
            ->map(function ($event) {
                $todaySession = EventAttendanceSession::where('event_id', $event->id)
                    ->where('attendance_date', now()->toDateString())
                    ->first();

                return [
                    'id'               => $event->id,
                    'title'            => $event->title,
                    'category'         => $event->category,
                    'start_date'       => $event->start_date,
                    'end_date'         => $event->end_date,
                    'today_session_id' => $todaySession?->id,
                    'is_locked'        => $todaySession ? !is_null($todaySession->locked_at) : false,
                ];
            });

        return response()->json(['data' => $events]);
    }

    /**
     * Create or retrieve the attendance session for a given event + date.
     * Body: { event_id, attendance_date (optional, defaults to today) }
     */
    #[OA\Post(
        path: '/api/v1/attendance/session',
        summary: 'Open or retrieve an attendance session for an event',
        operationId: '3795511bd2227894dd5bbdc9c58e3927',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/OpenSessionRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/OpenSessionResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function openSession(Request $request)
    {
        $request->validate([
            'event_id'        => 'required|integer|exists:events,id',
            'attendance_date' => 'nullable|date',
        ]);

        $event = Events::where('church_id', Auth::user()->church_id)
            ->where('id', $request->event_id)
            ->where('enable_attendance', true)
            ->firstOrFail();

        $assigned = EventManager::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->exists();

        if (!$assigned) {
            return response()->json(['message' => 'You are not assigned to this event.'], 403);
        }

        $date = $request->input('attendance_date', now()->toDateString());

        try {
            $session = EventAttendanceSession::firstOrCreate(
                ['event_id' => $event->id, 'attendance_date' => $date],
                [
                    'church_id' => Auth::user()->church_id,
                    'opened_by' => Auth::id(),
                ]
            );

            return response()->json([
                'session_id'      => $session->id,
                'event_id'        => $event->id,
                'event_title'     => $event->title,
                'attendance_date' => $session->attendance_date,
                'is_locked'       => !is_null($session->locked_at),
            ]);
        } catch (Exception $e) {
            Log::error('AttendanceController@openSession: ' . $e->getMessage());
            return response()->json(['message' => 'Could not open session.'], 500);
        }
    }

    /**
     * Scan a member QR code and mark them present.
     * Body: { session_id, member_username }
     * The QR code encodes url('/admin/attandance/{username}') — parse the username from it.
     */
    #[OA\Post(
        path: '/api/v1/attendance/scan',
        summary: 'Scan a member QR code and mark them present',
        operationId: '46ef9498ea664f07cdce9e54bdcb51ba',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/AttendanceScanRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/AttendanceScanResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function scan(Request $request)
    {
        $request->validate([
            'session_id'      => 'required|integer|exists:event_attendance_sessions,id',
            'member_username' => 'required|string',
        ]);

        $session = EventAttendanceSession::findOrFail($request->session_id);

        if ($session->church_id !== Auth::user()->church_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if (!is_null($session->locked_at)) {
            return response()->json(['message' => 'This session is locked. No further check-ins allowed.'], 403);
        }

        $member = User::where('name', $request->member_username)
            ->where('church_id', Auth::user()->church_id)
            ->first();

        if (!$member) {
            return response()->json(['message' => 'Member not found.'], 404);
        }

        $existing = EventAttendee::where('session_id', $session->id)
            ->where('user_id', $member->id)
            ->first();

        if ($existing) {
            return response()->json([
                'status'      => 'already_checked_in',
                'member_name' => $member->name,
                'avatar_url'  => $member->userprofile?->avatar
                    ? \Storage::disk('public')->url($member->userprofile->avatar)
                    : null,
                'scanned_at'  => $existing->scanned_at,
            ], 409);
        }

        try {
            $attendee = EventAttendee::create([
                'session_id' => $session->id,
                'church_id'  => Auth::user()->church_id,
                'event_id'   => $session->event_id,
                'user_id'    => $member->id,
                'scanned_at' => now(),
                'scanned_by' => Auth::id(),
            ]);

            return response()->json([
                'status'      => 'checked_in',
                'member_name' => $member->name,
                'avatar_url'  => $member->userprofile?->avatar
                    ? \Storage::disk('public')->url($member->userprofile->avatar)
                    : null,
                'scanned_at'  => $attendee->scanned_at,
            ]);
        } catch (Exception $e) {
            Log::error('AttendanceController@scan: ' . $e->getMessage());
            return response()->json(['message' => 'Check-in failed.'], 500);
        }
    }

    /**
     * Lock an attendance session (no further check-ins).
     */
    #[OA\Post(
        path: '/api/v1/attendance/session/{id}/lock',
        summary: 'Lock an attendance session to prevent further check-ins',
        operationId: 'a039bf0ec2aaaf6915e1efbaf6cead58',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/SessionLockResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function lock($session_id)
    {
        $session = EventAttendanceSession::findOrFail($session_id);

        if ($session->church_id !== Auth::user()->church_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $assigned = EventManager::where('event_id', $session->event_id)
            ->where('user_id', Auth::id())
            ->exists();

        if (!$assigned) {
            return response()->json(['message' => 'You are not assigned to this event.'], 403);
        }

        $session->update(['locked_at' => now(), 'locked_by' => Auth::id()]);

        return response()->json(['message' => 'Session locked successfully.', 'locked_at' => $session->locked_at]);
    }

    /**
     * Return the attendee list for a session.
     */
    #[OA\Get(
        path: '/api/v1/attendance/session/{id}',
        summary: 'Get the attendee report for a session',
        operationId: 'f89b40e7dca45ed008404928f0593bc7',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/SessionReportResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function sessionReport($session_id)
    {
        $session = EventAttendanceSession::with('event')->findOrFail($session_id);

        if ($session->church_id !== Auth::user()->church_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $attendees = EventAttendee::where('session_id', $session_id)
            ->with(['member.userprofile', 'scannedBy'])
            ->orderBy('scanned_at')
            ->get()
            ->map(function ($a) {
                return [
                    'member_id'   => $a->user_id,
                    'member_name' => $a->member->name,
                    'avatar_url'  => $a->userprofile?->avatar
                        ? \Storage::disk('public')->url($a->userprofile->avatar)
                        : null,
                    'mobile_no'   => $a->member->mobile_no,
                    'scanned_at'  => $a->scanned_at,
                    'scanned_by'  => $a->scannedBy?->name,
                ];
            });

        return response()->json([
            'session_id'      => $session->id,
            'event_title'     => $session->event->title,
            'attendance_date' => $session->attendance_date,
            'is_locked'       => !is_null($session->locked_at),
            'total'           => $attendees->count(),
            'attendees'       => $attendees,
        ]);
    }
}
