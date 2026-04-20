<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SermonLink;
use App\Events\PushEvent;
use App\Models\Bulletin;
use App\Models\Gallery;
use App\Models\Events;
use App\Models\Photos;
use App\Models\Sermon;
use App\Models\User;
use OpenApi\Attributes as OA;
/**
 * TestController
 *
 * Testing and validation endpoint for API functionality.
 * Provides endpoints for testing notifications, data grouping, and push events.
 * Used for development and debugging of API features and notification systems.
 *
 * @package App\Http\Controllers\Api
 */
class TestController extends Controller
{
    public function index()
    {
        $user=User::get()->groupBy('church_id');
        return $user;
    }

    public function events()
    {
        $event=Events::get()->groupBy('church_id');
        return $event;
    }

    public function gallery()
    {
        $gallery=Gallery::get()->groupBy('church_id');
        return $gallery;
    }

    public function notification(Request $request)
    {
        try
        {
            if($request->type==='event')
            {
                $events= new Events;

                $events->church_id    = Auth::user()->church_id;
                $events->select_type  = 'public';
                $events->title        = 'Prayer';
                $events->description  = 'Prayer Session';
                $events->repeats      = '0';
                $events->location     = 'Chennai';
                $events->category     = 'prayer';
                $events->organised_by = 'John Michael';
                $events->image        = 'uploads/images.jpg';
                $events->start_date   = '2019-11-24 10:00:00';
                $events->end_date     = '2019-11-25 10:00:00';

                $events->save();

                $data=[];

                $data['church_id'] = Auth::user()->church_id;
                $data['message']   = 'New Event created';
                $data['type']      = 'event';

                event(new PushEvent($data));

                $res['success']='Event Added Successfully';
                return $res;
            }
            elseif($request->type==='bulletin')
            {
                $church_id  = Auth::user()->church_id;
                $created_by = Auth::id();

                $bulletin   = new Bulletin;

                $bulletin->church_id = $church_id;
                $bulletin->name = 'Test';
                $bulletin->type = 'week';
                $bulletin->week = '1';
                $bulletin->year = '2016';
                $bulletin->cover_image    ='uploads/images.jpg';
                $bulletin->path = 'uploads/file.pdf';
                $bulletin->created_by = $created_by;
                $bulletin->save();

                $message=('Bulletin Added Successfully');

                $data=[];
                $data['church_id']=Auth::user()->church_id;
                $data['message']='New Bulletin created';
                $data['type']='bulletin';
                event(new PushEvent($data));

                $res['success']="Bulletin Added Successfully";
                return $res;
            }
            elseif($request->type==='gallery')
            {
                $church_id      = Auth::user()->church_id;

                $gallery = new Gallery;

                $gallery->church_id = $church_id;
                $gallery->name = 'Test';
                $gallery->description = 'Test';
                $gallery->path = 'uploads\images.jpg';
                $gallery->created_by = Auth::id();
                $gallery->updated_by = Auth::id();
                $gallery->save();

                $data=[];

                $data['church_id']=Auth::user()->church_id;
                $data['message']='New Folder created';
                $data['type']='gallery';

                event(new PushEvent($data));

                $res['success']="Gallery Added Successfully";
                return $res;
            }
            elseif($request->type==='photos')
            {
                $church_id      = Auth::user()->church_id;
                $created_by = Auth::id();
                $updated_by = Auth::id();

                $create = [
                    'gallery_id'  => '1',
                    'church_id'   => $church_id,
                    'path'        => 'uploads\images.jpg',
                    'created_by'  => $created_by,
                    'updated_by'  => $updated_by,
                ];

                $photo = Photos::create($create);

                $data=[];

                $data['church_id']=Auth::user()->church_id;
                $data['message']='New Photo Added';
                $data['type']='photos';

                event(new PushEvent($data));

                $res['message']="Uploaded Successfully";
                return $res;
            }
            elseif($request->type==='sermon')
            {
                $church_id      = Auth::user()->church_id;
                $user_id        = Auth::id();

                $sermon = new Sermon;

                $sermon->church_id   = $church_id;
                $sermon->user_id     = $user_id;
                $sermon->title       = 'Test';
                $sermon->description = 'Test';
                $sermon->cover_image = 'uploads\images.jpg';

                $sermon->save();

                $data=[];

                $data['church_id']=Auth::user()->church_id;
                $data['message']='New Sermon Created';
                $data['type']='sermon';

                event(new PushEvent($data));

                $res['message']="Sermon Created Successfully";
                return $res;
            }
            elseif($request->type==='sermonlink')
            {
                $church_id = Auth::user()->church_id;
                $user_id = Auth::id();

                $sermon=new SermonLink;
                $sermon->church_id  = $church_id;
                $sermon->user_id    = $user_id;
                $sermon->sermons_id = '1';
                $sermon->type       = 'document';
                $sermon->location   = 'chennai';
                $sermon->date       = '2019-10-20';
                $sermon->url        = 'uploads\file.pdf';

                $sermon->save();

                $data=[];

                $data['church_id']=Auth::user()->church_id;
                $data['message']='New SermonLink Created';
                $data['type']='sermonlink';

                event(new PushEvent($data));

                $res['success']='Series Uploaded Successfully';
                return $res;
            }
        }
        catch(Exception $e)
        {

        }
    }
    
#[OA\Get(
    path: "/api/test",
    summary: "Test API",
    responses: [
        new OA\Response(
            response: 200,
            description: "OK"
        )
    ]
)]
    public function test()
    {
        return response()->json(['message' => 'ok']);
    }

}
