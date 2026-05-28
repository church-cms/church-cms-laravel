<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\API\ShowEventGallery as ShowEventGalleryResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\EventGallery;
use OpenApi\Attributes as OA;   // ← add this line

/**
 * EventGalleryController
 *
 * Delivers event-specific photo galleries via API.
 * Returns images associated with specific church events.
 *
 * @package App\Http\Controllers\Api
 */
class EventGalleryController extends Controller
{
    #[OA\Get(
        path: '/api/v1/events/gallery/show/{event_id}',
        summary: 'Event Gallery Details',
        parameters: [
            new OA\Parameter(
                name: 'event_id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/EventGalleryResponse'
            )
        ]
    )]
    public function showimage($event_id)
    {
        $event = EventGallery::where([['event_id', $event_id], ['church_id', Auth::user()->church_id]])->paginate(10);

        $event = ShowEventGalleryResource::collection($event);
        return $event;
    }
}
