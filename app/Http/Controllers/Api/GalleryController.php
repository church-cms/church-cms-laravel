<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\API\ShowGallery as ShowGalleryResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Photos;
use App\Traits\Common;
use App\Http\Resources\API\ShowPhotos as ShowPhotosResource;
use OpenApi\Attributes as OA;   // ← add this line

/**
 * GalleryController
 *
 * Delivers gallery and photo content via API.
 * Returns gallery listings with associated photos.
 *
 * @package App\Http\Controllers\Api
 * @uses Common Trait for helper functions
 */
class GalleryController extends Controller
{
    use Common;

    public function show()
    {
        $slider = Gallery::with('photos')->get();
        $gallery = ShowGalleryResource::collection($slider);
        return response()->json($gallery);
    }

    public function view($gallery_id, $church_id)
    {
        $photos = Photos::where([['gallery_id', $gallery_id], ['church_id', $church_id]])->get();
        $photos = ShowPhotosResource::collection($photos);
        return response()->json($photos);
    }
    #[OA\Get(
        path: '/api/v1/gallery/show/{church_id}',
        tags: ['Gallery'],
        summary: 'show Gallery',
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
                ref: '#/components/responses/EventGalleryResponse'
            )
        ]
    )]

    public function showdetails($church_id)
    {
        $gallery = Gallery::where('church_id', $church_id)->latest()->paginate(10);
        $gallery = ShowGalleryResource::collection($gallery);

        return $gallery;
    }
}
