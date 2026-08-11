<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AudioRequest;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Models\MediaFile;
use App\Traits\Common;
use Exception;
use Log;

/**
 * AudioController
 *
 * Manages audio file uploads and sermon audio content management.
 * Handles audio file uploads, storage, and retrieval for sermon recordings.
 * Integrates with media file management and activity logging.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for activity tracking
 * @uses Common Trait for utility functions
 */
class AudioController extends Controller
{
    //
    use LogActivity;
    use Common;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin/mediafiles/audio/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAudio(Request $request)
    {
        try
        {
            $folder     = '/uploads/audio/'.Auth::user()->church_id;
            $filename   = date('d_m_Y_H_i_s').'_.'.$request->encoding;
            $path       = $this->putContentsByFilename($folder,$request->file,$filename);
            \Session::put('path',$path);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function audiostore(Request $request)
    {
        try
        {
            \Session::forget('path');
            $folder     = Auth::user()->church_id.'/uploads/files/audio';
            $path = $this->uploadFile($folder,$request->file);
            \Session::put('path',$path);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AudioRequest $request)
    {
        //

        try
        {
            $audio = new MediaFile;

            $audio->church_id       = Auth::user()->church_id;
            $audio->media_type      = 'audio';
            $audio->name            = $request->name;
            $audio->description     = $request->description;
            $audio->type            = $request->audio_type;

            if($request->audio_type === 'record')
            {
                $file = $request->file('audioupload');
                if($file)
                {

                     $folder     = Auth::user()->church_id.'/uploads/files/audio';
                     $filename   = 'audio_'.date('d_m_Y_H_i_s').'_audio.mp3';
                     $path  = $this->putContentsByFilename($folder,$request->audioupload,$filename);
                     $audio->url = $path;
                }
            }
            elseif($request->audio_type === 'attach')
            {
                $audio->url = \Session::get('path');
            }

            $audio->save();

            \Session::forget('path');

            $message = 'Audio Added Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $audio,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_AUDIO,
                $message
            );

            return redirect('/admin/mediafiles')->with('successmessage',$message);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
}
