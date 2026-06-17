<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\API\ActivityLog as ActivityLogResource;
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
        // dd("GG");

        $user_activity = ActivityLog::where('causer_id', Auth::user()->id)->latest()->paginate(10);

        //dd($user_activity);

        $user_log = ActivityLogResource::collection($user_activity);



        return $user_log;
    }
}
