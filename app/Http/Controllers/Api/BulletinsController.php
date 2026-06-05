<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\API\Bulletin as BulletinResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Bulletin;
use OpenApi\Attributes as OA;

/**
 * BulletinsController
 *
 * Provides bulletin content delivery via API for mobile/web consumption.
 * Returns latest church bulletins and announcements paginated.
 *
 * @package App\Http\Controllers\Api
 */
class BulletinsController extends Controller
{
    #[OA\Get(
        path: '/api/v1/bulletin/show',
        tags: ['Bulletins'],
        summary: 'List church bulletins',
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/BulletinResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function show()
    {
        $bulletins = Bulletin::where('church_id', Auth::user()->church_id)->latest()->paginate(10);
        $bulletins = BulletinResource::collection($bulletins);
        return $bulletins;
    }
}
