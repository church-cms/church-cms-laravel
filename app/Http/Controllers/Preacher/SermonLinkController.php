<?php

namespace App\Http\Controllers\Preacher;

use App\Http\Resources\EditSermonLink as EditSermonLinkResource;
use App\Http\Requests\SermonLinkUpdateRequest;
use App\Http\Requests\SermonLinkRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Events\SermonLinkEvent;
use App\Traits\LogActivity;
use App\Models\SermonLink;
use App\Models\Sermon;
use App\Traits\Common;
use Exception;
use Response;

/**
 * SermonLinkController
 *
 * Manages external links and resources associated with sermons by preachers.
 * Handles CRUD operations for sermon links with authorization checks.
 * Triggers events and sends notifications on sermon link activities.
 *
 * @package App\Http\Controllers\Preacher
 */
class SermonLinkController extends Controller
{
    //
    use LogActivity;
    use Common;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($sermons_id)
    {
        $sermon_id = Sermon::where('id', $sermons_id)->first();

        if (Gate::allows('sermon', $sermon_id)) {
            $sermon = SermonLink::with('church', 'user', 'sermons')->where([['church_id', Auth::user()->church_id], ['sermons_id', $sermons_id]])->paginate(5);

            return view('/preacher/sermonlink/create', ['sermons' => $sermon], ['sermon_id' => $sermon_id]);
        } else {
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SermonLinkRequest $request, $sermons_id)
    {
        try {
            $sermon = new SermonLink;

            $sermon->church_id  = Auth::user()->church_id;
            $sermon->user_id    = Auth::id();
            $sermon->sermons_id = $sermons_id;
            $sermon->title      = $request->title;
            $sermon->date       = date('Y-m-d', strtotime($request->date));
            $sermon->video_link = $request->video_link ?: null;
            $sermon->audio_link = $request->audio_link ?: null;

            if ($request->hasFile('pdf_link')) {
                $path = $this->uploadFile('/uploads/sermons/documents' . '/' . Auth::user()->church_id, $request->file('pdf_link'));
                $sermon->pdf_link = $path;
            }

            $sermon->save();
            if (env('MAIL_STATUS') === 'on') {
                event(new SermonLinkEvent($sermon));
            }

            $message = 'Series Uploaded Successfully';
            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $sermon,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_SERMONLINK,
                $message
            );

            $res['success'] = $message;
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function getDownload(Request $request, $id)
    {
        //PDF file is stored under project/public/download/info.pdf
        $sermon = SermonLink::where('id', $id)->first();

        if (Gate::allows('sermon', $sermon)) {
            $path = public_path('/' . $sermon->url);
            $file = pathinfo($path);

            $extension = $file['extension'];

            $headers = [
                'Content-Type: application/' . $extension,
            ];

            return Response::download($path, 'filename.' . $extension, $headers);
        } else {
            abort(403);
        }
    }

    public function edit($id)
    {
        $sermon = SermonLink::where('id', $id)->get();
        $sermon = EditSermonLinkResource::collection($sermon);

        return $sermon;
    }

    public function validateedit(Request $request)
    {
        //
    }

    public function update(SermonLinkUpdateRequest $request, $id)
    {
        try {
            $links = SermonLink::where('id', $id)->first();

            $links->title      = $request->title;
            $links->date       = date('Y-m-d', strtotime($request->date));
            $links->video_link = $request->video_link ?: null;
            $links->audio_link = $request->audio_link ?: null;

            if ($request->hasFile('pdf_link')) {
                $path = $this->uploadFile('/uploads/sermons/documents' . '/' . Auth::user()->church_id, $request->file('pdf_link'));
                $links->pdf_link = $path;
            }
            $links->save();

            $message = "Series Updated Successfully";
            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $links,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_SERMONLINK,
                $message
            );

            $res['success'] = 'Series Updated Successfully';
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $sermon = SermonLink::findOrFail($id);
            $sermon->delete();

            $message = "Sermonlink deleted";
            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $sermon,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_SERMONLINK,
                $message
            );

            return redirect()->back()->with(['successmessage' => 'Sermonlink deleted']);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
