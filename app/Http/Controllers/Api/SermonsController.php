<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\API\ShowSermon as ShowSermonResource;
use App\Http\Controllers\Controller;
use App\Models\Sermon;
use OpenApi\Attributes as OA;   // ← add this line
class SermonsController extends Controller
{
    #[OA\Get(
        path: '/api/v1/sermon/view/{church_id}',
        tags: ['Sermons'],
        summary: 'Get Sermon Details  ',
        parameters: [
            new OA\Parameter(
                name: 'church_id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/SermonsResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function index($church_id)
    {
        $sermon = Sermon::where('church_id', $church_id)->latest()->paginate(10);

        $sermon = ShowSermonResource::collection($sermon);

        return $sermon;
    }
}
