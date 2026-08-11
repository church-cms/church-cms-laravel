<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Resources\API\Guest\ShowGallery as ShowGalleryResource;
use App\Http\Resources\API\Guest\ShowPhotos as ShowPhotosResource;
use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Photos;

class GalleryController extends Controller
{
    //
    
	public function index($church_id)
    {
        $gallery = Gallery::where('church_id',$church_id)->paginate(10);

        $gallery = ShowGalleryResource::collection($gallery);
        
        return $gallery;
    }

    public function show($church_id,$gallery_id)
    {
        $photos = Photos::with('gallery')->where([['gallery_id',$gallery_id],['church_id',$church_id]])->paginate(10);

        $photos = ShowPhotosResource::collection($photos);

        return $photos; 
    }
}