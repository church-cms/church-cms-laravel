<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\EventGalleryRequest;
use App\Models\EventGallery;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use App\Http\Resources\ShowEventGallery as ShowEventGalleryResource;
use Log;

class EventGalleryController extends Controller
{

    use Common;
     use LogActivity;
/**
 * EventGalleryController
 *
 * Manages photo galleries associated with specific events.
 * Handles event-specific gallery creation, photo uploads, and deletion.
 * Supports file storage management and gallery organization.
 *
 * @package App\Http\Controllers\Admin
 * @uses Common Trait for file utilities
 * @uses LogActivity Trait for audit logging
 */
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
    public function getPhoto($event_id)
    {

       $event = EventGallery::where([['event_id',$event_id],['church_id',Auth::user()->church_id]])->get();
        return $event;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventGalleryRequest $request,$event_id)//EventGallery
    {
      try
      {
        if(count($request->data)!=null)
        {
          for ($i=0;$i<count($request->data);$i++)
          {
            $image_parts    = explode(";base64,",$request->data[$i]['path']);
            $image_type_aux = explode("image/",$image_parts[0]);
            $image_type     = $image_type_aux[1];
            $image_base64   = base64_decode($image_parts[1]);
            $church_id      = Auth::user()->church_id;
            $location        = Auth::user()->church_id.'/photos/events';


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
              'event_id'   => $event_id,
              'church_id'  => $church_id,
              'path'       => $db_path,
              'created_by' => Auth::id(),
              'updated_by' => Auth::id(),
            ];
            $photo = EventGallery::create($create);
          }

          $message=('Events photos added Successfully');

          $ip= $this->getRequestIP();
          $this->doActivityLog(
            $photo,
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
            LOGNAME_EVENT_PHOTO,
            $message
          );

          $res['message']="Uploaded Successfully";
          return $res;
        }
        else
        {
          $res['count']="Select Atleast One";
          return $res;
        }
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
    public function show($event_id)
    {
        $event = EventGallery::where([['event_id',$event_id],['church_id',Auth::user()->church_id]])->get();

        return $event;
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
            $photo = EventGallery::where('id',$id)->first();

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
