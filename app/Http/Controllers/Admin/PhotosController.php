<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Photos;
use App\Models\Gallery;
use App\Traits\Common;
use Carbon;
use File;
use Illuminate\Support\Facades\Redirect;
use App\Http\Resources\API\ShowPhotos as ShowPhotosResource;
use App\Traits\LogActivity;
use App\Http\Requests\EventGalleryRequest;
use Exception;
use Log;

/**
 * PhotosController
 *
 * Manages photos and image uploads for galleries and galleries.
 * Handles photo upload, deletion, and association with galleries.
 * Supports image validation and storage with file system management.\n *\n * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for file utilities
 */
class PhotosController extends Controller
{

    use LogActivity;
    use Common;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPhoto($gallery_id)
    {
        return Photos::where([['gallery_id',$gallery_id],['church_id',Auth::user()->church_id]])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$gallery_id)
    {
        try
        {
           for ($i=0;$i<count($request->data);$i++)
            {
                $image_parts    = explode(";base64,",$request->data[$i]['path']);
                $image_type_aux = explode("image/",$image_parts[0]);
                $image_type     = $image_type_aux[1];
                $image_base64   = base64_decode($image_parts[1]);
                $church_id      = Auth::user()->church_id;

                $location        = Auth::user()->church_id.'/gallery/covers/'.$gallery_id;


                $file            =  uniqid() .'.png';

                $db_path=$location.$file;
                /*if( ! File::isDirectory($location_path))
                {
                    File::makeDirectory($location_path,0777, true);
                }

                $path= $location_path. $file;
                $img = \File::put($path, $image_base64);*/
                $img = $this->putContents($db_path, $image_base64);

                $create = [

                 'gallery_id'   => $gallery_id,
                 'church_id'    => $church_id,
                 'path'         => $db_path,
                 'created_by'   => Auth::id(),
                 'updated_by'   => Auth::id(),
                ];
                $photo = Photos::create($create);
            }

            $message=('Photos Added Successfully');

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $photo,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_PHOTO,
                $message
            );
           $res['message']="Uploaded Successfully";
           return $res;
       }
       catch (Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($gallery_id)
    {
        $photos = Photos::where('gallery_id',$gallery_id)->first();
        if(Gate::allows('photo',$photos))
        {
            return $photos;
        }
          else
          {
            abort(403);
          }
    }

     public function showdetails($gallery_id)
    {

        $photos = Photos::where([['gallery_id',$gallery_id],['church_id',Auth::user()->church_id]])->get();
        $photos = ShowPhotosResource::collection($photos);
        return $photos;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try
        {
            $photo = Photos::where('id',$id)->first();

            $photo->delete();

            $message = 'Photo Deleted Successfully';


            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $photo,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_PHOTO,
                $message
            );
            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
