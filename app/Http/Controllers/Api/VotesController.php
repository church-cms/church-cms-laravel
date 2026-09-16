<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\VoteProcess;
use Exception;
use Log;
use OpenApi\Attributes as OA;   // ← add this line

/**
 * VotesController
 *
 * Manages user voting interactions (likes/unlikes) for content via API.
 * Handles creation and deletion of vote entries for various resources.
 * Uses VoteProcess trait for centralized voting logic and validation.
 *
 * @package App\Http\Controllers\Api
 */
class VotesController extends Controller
{
    use VoteProcess;

    #[OA\Post(
        path: '/api/v1/sermon/like',
        summary: 'Like a sermon',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/SermonLikeRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/SermonLikeResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function like(Request $request)
    {
        try {
            $vote = $this->createlikeVote($request, Auth::user()->church_id, Auth::id());
            return $vote;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
    #[OA\Post(
        path: '/api/v1/sermon/unlike',
        summary: 'UnLike a sermon',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/SermonUnLikeRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/SermonUnLikeResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function unlike(Request $request)
    {
        try {
            $vote = $this->createunlikeVote($request, Auth::user()->church_id, Auth::id());
            return $vote;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
