<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\API\ShowPhotos as ShowPhotosResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Photos;

class PhotosController extends Controller
{

	#[OA\Get(
		path: '/api/v1/gallery/show/{church_id}',
		tags: ['Gallery'],
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
				ref: '#/components/responses/PhotosResponse'
			)
		]
	)]
	public function showdetails($gallery_id)
	{
		$photos = Photos::with('gallery')->where([['gallery_id', $gallery_id], ['church_id', Auth::user()->church_id]])->paginate(10);
		$photos = ShowPhotosResource::collection($photos);

		return $photos;
	}
}
