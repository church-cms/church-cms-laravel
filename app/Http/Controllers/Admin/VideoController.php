<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\SendPushNotification;
use App\Http\Requests\VideoRequest;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Events\PushEvent;
use App\Models\MediaFile;
use App\Traits\Common;
use Exception;
use Log;

/**
 * VideoController
 *
 * Manages video file uploads and video media content.
 * Handles video upload, processing, and storage.
 * Supports subscription-based video feature limits and push notifications.
 *
 * @package App\Http\Controllers\Admin
 * @uses SendPushNotification Trait for video notifications
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for file utilities
 */
class VideoController extends Controller
{
    use SendPushNotification;
    use LogActivity;
    use Common;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $video = MediaFile::where('church_id', Auth::user()->church_id)->get();
        $count = MediaFile::where('church_id', Auth::user()->church_id)->count();


        return view('/admin/mediafiles/video/create', ['videos' => $video, 'count' => $count]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeVideo(Request $request)
    {
        try {
            $filename   = date('d_m_Y_H_i_s') . '_video.mp4';
            $folder     = '/uploads/video/' . Auth::user()->church_id;

            $path = \Storage::disk('s3')->putFileAs($folder, $request->file, $filename);
            \Session::put('path', $path);
        } catch (Exception $e) {
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VideoRequest $request)
    {
        try {
            $video                  = new MediaFile;

            $video->church_id       = Auth::user()->church_id;
            $video->media_type      = 'video';
            $video->name            = $request->input('name');
            $video->description     = $request->input('description');
            $video->type            = $request->video_type;
            if ($request->video_type === 'url') {
                $video->url             = $request->videourl;
            } else {
                $video->url = \Session::get('path');
            }

            $video->save();

            \Session::forget('path');

            $data = [];

            $data['church_id']  =   Auth::user()->church_id;
            $data['message']    =   'New Video Added';
            $data['type']       =   'video';

            event(new PushEvent($data));

            $message = 'Videos Added Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $video,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_VIDEO,
                $message
            );

            $res['success'] = $message;
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
