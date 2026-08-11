<?php

namespace App\Http\Controllers\Preacher;

use App\Http\Requests\SermonUpdateRequest;
use App\Http\Requests\SermonRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Events\SermonEvent;
use App\Models\SermonLink;
use App\Traits\Common;
use App\Models\Sermon;
use Exception;
use Log;

/**
 * SermonsController
 *
 * Manages sermon creation, updates, and deletion by preachers.
 * Handles sermon CRUD operations with event triggers and logging.
 * Manages sermon relationships with sermon links and metadata.
 *
 * @package App\Http\Controllers\Preacher
 */
class SermonsController extends Controller
{
    //
    use LogActivity;
    use Common;

    public function index(Request $request)
    {
        //
        $sermon = Sermon::where('church_id',Auth::user()->church_id)->with('vote');

        $q = request('q');
        if($q!= '')
        {
            $sermon= Sermon::where('church_id',Auth::user()->church_id)->where(function ($query) use($q)
            {
                $query->where('title','LIKE','%'.$q.'%');

            });
        }
        $sermon=$sermon->paginate(9);

        return view('preacher.sermon.index',['sermon'=> $sermon])->withQuery ( $q );
    }

    public function create()
    {
        return view('preacher.sermon.create');
    }

    public function store(SermonRequest $request)
    {
        try
        {
            $church_id      = Auth::user()->church_id;
            $user_id        = Auth::id();
            $file = $request->file('cover_image');
            $path = $this->uploadFile('/uploads/sermons/covers/1',$file);

            $sermon = new Sermon;

            $sermon->church_id   = $church_id;
            $sermon->user_id     = $user_id;
            $sermon->title       = $request->title;
            $sermon->description = $request->description;
            $sermon->cover_image = $path;

            $sermon->save();

            if(env('MAIL_STATUS') === 'on')
            {
                event(new SermonEvent($sermon));
            }

            return redirect('/preacher/sermon/create')->with('successmessage', 'Sermon Created!');

            $message = 'Sermon Added Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $sermon,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_SERMON,
                $message
            );
        }
        catch (Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function edit($id)
    {
        $sermon=Sermon::where('id',$id)->first();
        return view('preacher.sermon.edit',['sermon'=> $sermon]);
    }

    public function update(SermonUpdateRequest $request,$id)
    {
        try
        {
            $church_id      = Auth::user()->church_id;
            $user_id        = Auth::id();
            $file = $request->file('cover_image');

            $sermon =Sermon::find($id);

            $sermon->church_id   = $church_id;
            $sermon->user_id     = $user_id;
            $sermon->title       = $request->title;
            $sermon->description = $request->description;

            if($file){
            $path = $this->uploadFile('/uploads/sermons/covers/1',$file);
            $sermon->cover_image = $path;
            }

            $sermon->save();

            if(env('MAIL_STATUS') === 'on')
            {
                event(new SermonEvent($sermon));
            }

            return redirect('/preacher/sermon/edit/'.$sermon->id)->with('successmessage', 'Sermon Updated!');

            $message = 'Sermon updated Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $sermon,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_UPDATE_SERMON,
                $message
            );
        }
        catch (Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function destroy($id)
    {
        try
        {
            $sermon = Sermon::findOrFail($id);
            $links = SermonLink::where([['sermons_id',$id],['user_id',Auth::id()]]);
            $links->delete();
            $sermon->delete();

            $message = "Sermon deleted";
            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $sermon,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_SERMON,
                $message
            );

            return redirect('/preacher/sermon')->with(['successmessage' => 'Sermon deleted']);
        }
        catch (Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
