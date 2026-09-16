<?php

namespace App\Http\Controllers\Admin;

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
use Illuminate\Http\Request;
use Exception;
use Response;
use Log;
use App\Events\PushEvent;
use App\Events\Notification\PushNotificationEvent;

class SermonLinkController extends Controller
{
    use LogActivity;
    use Common;

    public function create($sermons_id)
    {
        $sermon_id = Sermon::where('id', $sermons_id)->firstOrFail();

        if (Gate::allows('sermon', $sermon_id)) {
            $sermon = SermonLink::with('church', 'user', 'sermons')
                ->where([['church_id', Auth::user()->church_id], ['sermons_id', $sermons_id]])
                ->paginate(5);

            return view('admin/sermonlink/create', ['sermons' => $sermon, 'sermon_id' => $sermon_id]);
        }

        abort(403);
    }

    public function store(SermonLinkRequest $request, $sermons_id)
    {
        try {
            $sermon             = new SermonLink;
            $sermon->church_id  = Auth::user()->church_id;
            $sermon->user_id    = Auth::id();
            $sermon->sermons_id = $sermons_id;
            $sermon->title      = $request->title;
            $sermon->date       = date('Y-m-d', strtotime($request->date));
            $sermon->video_link = $request->video_link ?: null;
            $sermon->audio_link = $request->audio_link ?: null;

            if ($request->hasFile('pdf_link')) {
                $sermon->pdf_link = $this->uploadFile('/uploads/sermons/documents/' . Auth::user()->church_id, $request->file('pdf_link'));
            }

            $sermon->save();

            if (env('MAIL_STATUS') === 'on') {
                event(new SermonLinkEvent($sermon));
            }
            $data = [];

            $data['church_id'] =  Auth::user()->church_id;
            $data['message'] = 'New SermonLink Created';
            $data['type'] = 'sermonlink';
            $data['id'] = $sermons_id;

            event(new PushEvent($data));

            $array = [
                'church_id' => Auth::user()->church_id,
                'details'   => 'New SermonLink Created',
                'message_type' => 'sermonlink',
                'message_id' => $sermons_id
            ];
            event(new PushNotificationEvent($array));

            $message = 'Series Uploaded Successfully';
            $ip      = $this->getRequestIP();
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
            Log::error($e->getMessage());
        }
    }

    public function getDownload(Request $request, $id)
    {
        $sermon = SermonLink::where('id', $id)->firstOrFail();

        if (Gate::allows('sermon', $sermon)) {
            $path      = public_path('/' . $sermon->url);
            $file      = pathinfo($path);
            $extension = $file['extension'];
            $headers   = ['Content-Type: application/' . $extension];

            return Response::download($path, 'filename.' . $extension, $headers);
        }

        abort(403);
    }

    public function edit($id)
    {
        $sermon = SermonLink::where('id', $id)->get();
        return EditSermonLinkResource::collection($sermon);
    }

    public function validateedit(Request $request)
    {
        //
    }

    public function update(SermonLinkUpdateRequest $request, $id)
    {
        try {
            $links             = SermonLink::where('id', $id)->firstOrFail();
            $links->title      = $request->title;
            $links->date       = date('Y-m-d', strtotime($request->date));
            $links->video_link = $request->video_link ?: null;
            $links->audio_link = $request->audio_link ?: null;

            if ($request->hasFile('pdf_link')) {
                $links->pdf_link = $this->uploadFile('/uploads/sermons/documents/' . Auth::user()->church_id, $request->file('pdf_link'));
            }

            $links->save();

            $message = 'Series Updated Successfully';
            $ip      = $this->getRequestIP();
            $this->doActivityLog(
                $links,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_SERMONLINK,
                $message
            );

            $res['success'] = $message;
            return $res;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $sermon = SermonLink::findOrFail($id);
            $sermon->delete();

            $message = 'Sermonlink deleted';
            $ip      = $this->getRequestIP();
            $this->doActivityLog(
                $sermon,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_SERMONLINK,
                $message
            );

            return redirect()->back()->with(['successmessage' => 'Sermonlink deleted']);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
