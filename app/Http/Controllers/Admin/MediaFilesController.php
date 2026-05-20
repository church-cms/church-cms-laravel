<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\MediaFile as MediaFileResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Models\MediaFile;
use App\Traits\Common;
use Exception;
use Log;

/**
 * MediaFilesController
 *
 * Manages media file uploads and document storage.
 * Handles file upload, categorization by media type, and file management.
 * Supports document and media file organization.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for file utilities
 */
class MediaFilesController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request,$type)
    {
        //
        $files = MediaFile::where([['church_id',Auth::user()->church_id],['media_type',$type]]);
        if(\Request::getQueryString() != null)
        {
            if($request->search != null)
            {
                $files = $files->where('name','LIKE','%'.$request->search.'%')->orWhere('description','LIKE','%'.$request->search.'%');
            }
        }
        $files = $files->get();

        $files = MediaFileResource::collection($files);

        return $files;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type   = in_array($request->input('type'), ['audio', 'video', 'image'])
                    ? $request->input('type') : 'image';
        $search = $request->input('search', '');

        $query = MediaFile::where('church_id', Auth::user()->church_id)
                    ->where('media_type', $type);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $files = $query->orderBy('created_at', 'desc')->paginate(24)->withQueryString();
        $count = $files->total();

        return view('admin.mediafiles.index', compact('files', 'count', 'type', 'search'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $file  = MediaFile::where('id',$id)->first();

        if($file->media_type === 'audio')
        {
            $url = $file->UrlPath;
        }
        else
        {
            if($file->type === 'url')
            {
                $url = $file->url;
            }
            else
            {
                $url = $file->UrlPath;
            }
        }
        return [
            //
            'id'            =>  $file->id,
            'name'          =>  $file->name,
            'description'   =>  $file->description,
            'media_type'    =>  $file->media_type,
            'type'          =>  $file->type,
            'url'           =>  $url,
        ];
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
            $file = MediaFile::where('id',$id)->first();
            $file->delete();

            $message='File Deleted Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $file,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                $message,
                $message
            );
            $res['success'] = $message;

            return redirect('/admin/mediafiles?type=video&search=&page=1')->with('successmessage',$message);


            //return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
