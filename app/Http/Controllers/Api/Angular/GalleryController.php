<?php

namespace App\Http\Controllers\Api\Angular;

use App\Http\Resources\API\ShowGallery as ShowGalleryResource;
use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Photos;
use App\Traits\Common;
use App\Http\Resources\API\ShowPhotos as ShowPhotosResource;
use App\Models\Church;

class GalleryController extends Controller
{
    use Common;

    public function show($slug)
    {
        $church = Church::where('slug', '=', $slug)->first();
        $slider = Gallery::where('church_id', $church->id)->with('photos')->get();
        $gallery = ShowGalleryResource::collection($slider);
        return response()->json($gallery);
    }

    public function view($slug, $gallery_id)
    {
        $church = Church::where('slug', '=', $slug)->first();
        $photos = Photos::where([['gallery_id', $gallery_id], ['church_id', $church->id]])->get();
        $photos = ShowPhotosResource::collection($photos);
        return response()->json($photos);
    }

    /*	public function showdetails($slug,$church_id)
    {
        $gallery = Gallery::where('church_id',$church_id)->paginate(10);
        $gallery = ShowGalleryResource::collection($gallery);
        
        return $gallery;
    }*/
}
