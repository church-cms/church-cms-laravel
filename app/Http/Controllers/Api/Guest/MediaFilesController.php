<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Resources\API\Guest\Video as VideoResource;
use App\Http\Resources\API\Guest\Audio as AudioResource;
use App\Http\Controllers\Controller;
use App\Models\MediaFile;

class MediaFilesController extends Controller
{
    public function audio($church_id)
    {
        $files = MediaFile::where([['church_id',$church_id],['media_type','audio']])->latest()->paginate(10);
        $files = AudioResource::collection($files);

        return $files;
    }

    public function video($church_id)
    {
        $files = MediaFile::where([['church_id',$church_id],['media_type','video']])->latest()->paginate(10);
        $files = VideoResource::collection($files);

        return $files;
    }
}