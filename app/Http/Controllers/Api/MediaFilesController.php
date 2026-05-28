<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\API\MediaFile as MediaFileResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MediaFile;
use OpenApi\Attributes as OA;

/**
 * MediaFilesController
 *
 * Delivers media files and documents via API.
 * Returns church media file listings with pagination.
 *
 * @package App\Http\Controllers\Api
 */
class MediaFilesController extends Controller
{
    #[OA\Get(
        path: '/api/v1/mediaFiles',
        summary: 'Get Media Files',
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/MediaFileResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function showvideo()
    {
        $files = MediaFile::where('church_id', Auth::user()->church_id)->latest()->paginate(10);
        $files = MediaFileResource::collection($files);

        return $files;
    }
}
