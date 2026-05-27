<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\FavoriteProcess;
use Illuminate\Http\Request;
use App\Models\SermonLink;
use App\Models\Favorite;
use App\Traits\Common;
use App\Models\Sermon;
use Exception;
use Log;
use OpenApi\Attributes as OA;   // ← add this line

/**
 * FavoritesController
 *
 * Manages user favorite items (sermons, posts, events) via API.
 * Handles adding and removing items from user favorites.
 *
 * @package App\Http\Controllers\Api
 * @uses FavoriteProcess Trait for favorite management
 * @uses Common Trait for helper functions
 */
class FavoritesController extends Controller
{
    use FavoriteProcess;
    use Common;
    #[OA\Post(
        path: '/api/v1/sermon/favorite',
        summary: 'Favorite',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/FavoritesRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/FavoritesResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function favorites(Request $request)
    {
        try {
            $church_id      = Auth::user()->church_id;
            $user_id        = Auth::id();
            $sermon = Sermon::where([['church_id', $church_id], ['id', $request->entity_id]])->first();
            if ($church_id === $sermon->church_id) {
                $entity_name = get_class($sermon);
                $entity_id = $sermon->id;
                $existing_favorite = Favorite::where([['church_id', $church_id], ['user_id', $user_id]])->exists();

                if (!($existing_favorite)) {
                    $favorite = $this->favoriteProcess($church_id, $user_id, $entity_id, $entity_name);
                } else {
                    $existing_favorite = Favorite::where([['church_id', $church_id], ['user_id', $user_id]])->first();

                    $existing_favorite->delete();
                }
            } else {
                return "Invalid";
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
