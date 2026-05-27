<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\API\ShowSermonLink as ShowSermonLinkResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\SermonLink;
use App\Models\Sermon;
use OpenApi\Attributes as OA;   // ← add this line
/**
 * SermonLinkController
 *
 * Manages sermon links and external resources associated with sermons via API.
 * Retrieves paginated sermon link resources for a specific sermon.
 * Returns formatted sermon link data with related sermon information.
 *
 * @package App\Http\Controllers\Api
 */
class SermonLinkController extends Controller
{
    #[OA\Get(
        path: '/api/v1/sermon/show/{sermons_id}',
        parameters: [
            new OA\Parameter(
                name: 'sermons_id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/SermonlinkResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function showdetails($sermons_id)
    {
        $sermon = Sermon::where('id', $sermons_id)->first();
        $links = SermonLink::with('sermons')->where([['sermons_id', $sermon->id], ['church_id', Auth::user()->church_id]])->paginate(10);

        $links = ShowSermonLinkResource::collection($links);

        return $links;
    }
}
