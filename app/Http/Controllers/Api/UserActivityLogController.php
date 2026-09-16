<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\GroupLink;
use App\Models\Sermon;
use App\Models\Events;
use Carbon\Carbon;
use OpenApi\Attributes as OA;

class UserActivityLogController extends Controller
{
    #[OA\Get(
        path: '/api/v1/member/activitylog',
        tags: ['User'],
        summary: 'Get paginated activity log for the authenticated member',
        operationId: 'd2e3f4a5b6c7d8e9f0a1b2c3d4e5f6a7',
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/ActivityLogResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function index()
    {
        $user_log  = [];
        $church_id = Auth::user()->church_id;
        $user_id   = Auth::id();

         $end_date = Carbon::now();
    
        // ── Events ────────────────────────────────────────────────────────────
        $events = Events::where([['church_id', Auth::user()->church_id], ['end_date', '>=', $end_date]])->latest()->take(2)->get();

        foreach ($events as $event) {
            $user_log[] = [
                'id'          => $event->id,
                'name'        => $event->title,
                'description' => $event->description ?? null,
                'status'      => 'soon',
                'date'        => $event->created_at->format('d-m-Y h:i A'),
                'type'        => 'event',
                'type_id'     => $event->id,
            ];
        }

        // ── Sermons (today) ───────────────────────────────────────────────────
        $sermons = Sermon::where('church_id', $church_id)
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->take(2)
            ->get();

        foreach ($sermons as $sermon) {
            $user_log[] = [
                'id'          => $sermon->id,
                'name'        => $sermon->title,
                'description' => $sermon->description ?? null,
                'status'      => 'new',
                'date'        => $sermon->created_at->format('d-m-Y h:i A'),
                'type'        => 'sermon',
                'type_id'     => $sermon->id,
            ];
        }

        // ── User's Groups (GroupLink) ─────────────────────────────────────────
        $groupLinks = GroupLink::with('group')
            ->where('user_id', $user_id)
            ->where('church_id', $church_id)
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->take(2)
            ->get();

        foreach ($groupLinks as $gl) {
            $memberCount = GroupLink::where('group_id', $gl->group_id)->count();
            $user_log[]  = [
                'id'          => $gl->id,
                'name'        => $gl->group->name,
                'description' => 'Group ' . $memberCount . ' members active',
                'status'      => 'active',
                'date'        => $gl->created_at->format('d-m-Y h:i A'),
                'type'        => 'group',
                'type_id'     => $gl->group_id,
            ];
        }

        return $user_log;
    }
}
