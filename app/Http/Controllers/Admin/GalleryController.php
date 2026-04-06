<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ShowGallery as ShowGalleryResource;
use App\Http\Requests\GalleryUpdateRequest;
use App\Http\Requests\GalleryRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Traits\LogActivity;
use App\Events\PushEvent;
use App\Models\Gallery;
use App\Models\Photos;
use App\Models\Events;
use App\Traits\Common;
use Exception;
use Log;
use App\Events\Notification\PushNotificationEvent;

/**
 * GalleryController
 *
 * Manages photo galleries and image collections within the church.
 * Handles gallery creation, photo uploads, gallery management, and event-specific galleries.
 * Implements subscription-based feature restrictions and push notifications for new galleries.
 * Supports search and filtering of gallery content.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class GalleryController extends Controller
{

    use LogActivity;
    use Common;

    public function index(Request $request)
    {
        $gallery = Gallery::with('photos')->where('church_id',Auth::user()->church_id);
        $count = Gallery::with('photos')->where('church_id',Auth::user()->church_id)->count();
        $subscription = Subscription::with('user','church')->where('church_id',Auth::user()->church_id)->first();

        $query = $request->get('search');
        if($query != null)
        {
            $gallery = $gallery->where('name','LIKE','%'.$query.'%');
        }

        $gallery = $gallery->get();

        return view('admin.gallery.index',['count' => $count , 'subscription' => $subscription , 'gallery' => $gallery]);
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(GalleryRequest $request)
    {
        try
        {
            $gallery = new Gallery;

            $gallery->church_id    = Auth::user()->church_id;
            $gallery->name         = $request->name;
            $gallery->description  = $request->description;

            $file = $request->file('path');
            if($file != null)
            {
                $folder = Auth::user()->church_id.'/gallery/covers';
                $gallery->path = $this->uploadFile($folder,$file);
            }

            $gallery->save();

            $data=[];

            $data['church_id']=Auth::user()->church_id;
            $data['message']='New Gallery Album Created';
            $data['type']='gallery';

            event(new PushEvent($data));

            $array=[];

            $array['church_id']=Auth::user()->church_id;
            $array['details']='New Gallery Album Created';

            event(new PushNotificationEvent($array));

            $message = 'Gallery Album Created Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $gallery,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_GALLERY_ALBUM,
                $message
            );

            return redirect('/admin/gallery')->with(['successmessage' => $message]);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function edit($id)
    {
        $gallery = Gallery::where('id',$id)->first();

        return view('admin.gallery.edit',['gallery' => $gallery]);
    }

    public function update(GalleryUpdateRequest $request,$id)
    {
        try
        {
        	$gallery = Gallery::where('id',$id)->first();

        	$gallery->name         = $request->name;
        	$gallery->description  = $request->description;

            $file = $request->file('path');
            if($file != null)
            {
                $folder = Auth::user()->church_id.'/gallery/covers';
                $gallery->path = $this->uploadFile($folder,$file);
            }
            else
            {
                $gallery->path = $gallery->path;
            }

        	$gallery->save();

            $data=[];

            $data['church_id']=Auth::user()->church_id;
            $data['message']='Gallery Album Updated';
            $data['type']='gallery';

            event(new PushEvent($data));

            $message = 'Gallery Album Updated Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $gallery,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_GALLERY_ALBUM,
                $message
            );

            return redirect('/admin/gallery')->with(['successmessage' => $message]);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function show($id)
    {
        $gallery = Gallery::where([['id',$id],['church_id',Auth::user()->church_id]])->first();

        if(count($gallery)>0)
        {
            if(Gate::allows('gallery',$gallery))
            {
                return view('admin.gallery.show')->with('gallery', $gallery);
            }
            else
            {
                abort(403);
            }
        }
        else
        {
            abort(403);
        }
    }

    public function showdetails($church_id)
    {
        $gallery = Gallery::where('church_id',$church_id)->get();
        $gallery = ShowGalleryResource::collection($gallery);
        return $gallery;
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
            $group = Gallery::where('id',$id)->first();

            $group->delete();

            $message = 'Gallery Album Deleted Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $group,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_GALLERY_ALBUM,
                $message
            );

            return redirect('/admin/gallery')->with(['successmessage' => $message]);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
