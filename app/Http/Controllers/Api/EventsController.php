<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\API\ShowEvent as ShowEventResource;
use App\Http\Resources\API\Events as EventsResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Events;
use Carbon\Carbon;
use OpenApi\Attributes as OA;   // ← add this line

/**
 * EventsController
 *
 * Provides event listings and detailed event information via API.
 * Returns upcoming and past events with filtering by date range.
 *
 * @package App\Http\Controllers\Api
 */
class EventsController extends Controller
{
    #[OA\Get(
        path: '/api/v1/events/upcoming',
        tags: ['Events'],
        summary: 'Upcomeing Event List',

        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/EventResponse'
            )
        ]
    )]
    public function upcoming()
    {
        $end_date = Carbon::now();
        $event = Events::where([['church_id', Auth::user()->church_id], ['end_date', '>=', $end_date]])->get();
        $upcomingevent = ShowEventResource::collection($event);

        return $upcomingevent;
    }
    #[OA\Get(
        path: '/api/v1/events/past',
        tags: ['Events'],
        summary: 'Past Event List',
        parameters: [
            new OA\Parameter(
                name: 'month',
                in: 'query',
                required: false,
                description: 'Month (1-12)',
                schema: new OA\Schema(
                    type: 'integer',
                    minimum: 1,
                    maximum: 12,
                    example: 5
                )
            ),
            new OA\Parameter(
                name: 'year',
                in: 'query',
                required: false,
                description: 'Year',
                schema: new OA\Schema(
                    type: 'integer',
                    minimum: 2000,
                    maximum: 2050,
                    example: 2026
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/EventResponse'
            )
        ]
    )]
    public function past()
    {
        $end_date = Carbon::now();
        $month = request('month');
        $year  = request('year');

        $query = Events::where('church_id', Auth::user()->church_id);

        if (!empty($month)) {
            $query->whereMonth('end_date', $month);
        }

        if (!empty($year)) {
            $query->whereYear('end_date', $year);
        }

        if (empty($month) && empty($year)) {
            $query->where('end_date', '<', $end_date);
        }

        $event = $query->get();

        //dd($event);

        $pastevent = ShowEventResource::collection($event);

        return $pastevent;
    }
    #[OA\Get(
        path: '/api/v1/event/show/{id}',
        tags: ['Events'],
        summary: 'Event Details',

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
                ref: '#/components/responses/EventResponse'
            )
        ]
    )]

    public function show($id)
    {
        $event = Events::where([['church_id', Auth::user()->church_id], ['id', $id]])->get();
        $event = ShowEventResource::collection($event);

        return $event;
    }
}
