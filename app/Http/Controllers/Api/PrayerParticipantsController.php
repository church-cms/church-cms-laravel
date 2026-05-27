<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PrayerParticipant;
use App\Models\Prayer;
use Illuminate\Http\Request;
use Exception;
use Log;
use OpenApi\Attributes as OA;

class PrayerParticipantsController extends Controller
{
    /**
     * Record that the current user is praying for a prayer.
     * Handles MEMBER (authenticated) and GUEST participation.
     * Returns idempotently — lifting the same prayer twice is a no-op.
     */
    #[OA\Post(
        path: '/api/v1/prayer_participants/{id}',
        summary: 'Record that the current user is praying for a prayer request',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'Prayer request ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 201,
                ref: '#/components/responses/PrayerParticipantResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function store(Request $request, $prayerId)
    {
        try {
            $churchId = Auth::user()->church_id;

            $prayer = Prayer::forChurch($churchId)
                ->active()
                ->findOrFail($prayerId);

            $type = PrayerParticipant::TYPE_MEMBER;
            PrayerParticipant::lift($prayer, Auth::user(), $type, null);

            return response()->json(['message' => 'Thank you for praying!'], 201);
        } catch (Exception $e) {
            Log::error('PrayerParticipantsController@store: ' . $e->getMessage());
            return response()->json(['message' => 'Could not record your prayer']);
        }
    }
}
