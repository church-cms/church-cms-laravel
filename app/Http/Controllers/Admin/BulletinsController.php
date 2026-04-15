<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Bulletin as BulletinResource;
use App\Events\Notification\PushNotificationEvent;
use App\Http\Requests\BulletinRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Events\PushEvent;
use App\Models\Bulletin;
use App\Traits\Common;
use Carbon\Carbon;
use Exception;
use App\Http\Requests\EditBulletinRequest;
class BulletinsController extends Controller
{
    use LogActivity;
    use Common;

/**
 * BulletinsController
 *
 * Manages church bulletins and weekly announcements.
 * Handles bulletin creation, updates, and distribution to church members.
 * Supports push notifications for new bulletin releases.
 * Integrates with subscription-based bulletin features.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request,$type)
    {
        //
        $bulletins = Bulletin::where([['church_id',Auth::user()->church_id],['type',$type]])->orderBy('year','desc');
        if(\Request::getQueryString() != null)
        {
            if($request->search != null)
            {
                $bulletins = $bulletins->where('name','LIKE','%'.$request->search.'%');
            }
        }
        $bulletins = $bulletins->get();

        if($type === 'month')
        {
            $bulletins = BulletinResource::collection($bulletins)->groupBy([function($bulletin) {
                return date("F", mktime(0, 0, 0, $bulletin->month, 1)).' '.$bulletin->year;
            }]);
        }
        else
        {
            $bulletins = BulletinResource::collection($bulletins)->groupBy([function($bulletin) {
                return 'Week - '.$bulletin->week.' '.$bulletin->year;
            }]);
        }
        return $bulletins;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('/admin/bulletins/index');
    }

    public function getDate()
    {
        $array =[];
        $start = Carbon::now()->format('Y');
        $end = Carbon::now()->subYears(25)->format('Y');
        $array['start'] = $start;
        $array['end'] = $end;

        return $array;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $count = Bulletin::where('church_id',Auth::user()->church_id)->count();
        return view('/admin/bulletins/create',['count' => $count]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BulletinRequest $request)
    {
        //
        try
        {
            $bulletin = new Bulletin;

            $bulletin->church_id = Auth::user()->church_id;
            $bulletin->name = $request->name;
            $bulletin->type = $request->type;
            if($bulletin->type === "week")
            {
                $bulletin->week = $request->week;
                $bulletin->cover_image = 'uploads/week.jpg';
            }
            if($bulletin->type === "month")
            {
                $bulletin->month = $request->month;
                $bulletin->cover_image = 'uploads/month.jpg';
            }
            $bulletin->year = $request->year;

            $bulletin_file = $request->file('path');
            if($bulletin_file)
            {
                $folder = Auth::user()->church_id.'/bulletins';
                $bulletin_file_path = $this->uploadFile($folder,$bulletin_file);
                $bulletin->path = $bulletin_file_path;
            }

             $cover_image = $request->file('cover_image');
            if($cover_image)
            {
                $folder = Auth::user()->church_id.'/bulletins/cover';
                $cover_image = $this->uploadFile($folder,$cover_image);
                $bulletin->cover_image = $cover_image;
            }
            $bulletin->created_by = Auth::id();
            $bulletin->save();

            $message=('Bulletin Added Successfully');

            $data=[];

            $data['church_id']=Auth::user()->church_id;
            $data['message']='New Bulletin created';
            $data['type']='bulletin';

            event(new PushEvent($data));

            $array=[];

            $array['church_id']=Auth::user()->church_id;
            $array['details']='New Bulletin Received';

            event(new PushNotificationEvent($array));

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $bulletin,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_BULLETIN,
                $message
            );

            $res['success']="Bulletin Added Successfully";
            return $res;
        }
        catch (Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
     
    public function edit($id)
    {
        //
        $count = Bulletin::where('church_id',Auth::user()->church_id)->count();
       
        return view('/admin/bulletins/edit',['count' => $count]);
    }

     public function getdetails($id){

         $data = Bulletin::where('id',$id)->first();

         $data['cover_image']=$data->CoverImagePath;

          

         return $data;

     }

    public function update($id,EditBulletinRequest $request)
    {
        //
        try
        {
            $bulletin = Bulletin::where('id',$id)->first();

            $bulletin->church_id = Auth::user()->church_id;
            $bulletin->name = $request->name;
            $bulletin->type = $request->type;

            if($bulletin->type === "week")
            {
                $bulletin->week = $request->week;
               // $bulletin->cover_image = 'uploads/week.jpg';
            }
            if($bulletin->type === "month")
            {
                $bulletin->month = $request->month;
                //$bulletin->cover_image = 'uploads/month.jpg';
            }
            $bulletin->year = $request->year;

            $bulletin_file = $request->file('path');
            if($bulletin_file)
            {
                $folder = Auth::user()->church_id.'/bulletins';
                $bulletin_file_path = $this->uploadFile($folder,$bulletin_file);
                $bulletin->path = $bulletin_file_path;
            }

             $cover_image = $request->file('cover_image');
            if($cover_image)
            {
                $folder = Auth::user()->church_id.'/bulletins/cover';
                $cover_image = $this->uploadFile($folder,$cover_image);
                $bulletin->cover_image = $cover_image;
            }
            //$bulletin->created_by = Auth::id();
            $bulletin->save();

            $message=('Bulletin Updated Successfully');

            $data=[];

            $data['church_id']=Auth::user()->church_id;
            $data['message']='Bulletin Updated';
            $data['type']='bulletin';

            event(new PushEvent($data));

            $array=[];

            $array['church_id']=Auth::user()->church_id;
            $array['details']='Bulletin Updated';

            event(new PushNotificationEvent($array));

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $bulletin,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_BULLETIN,
                $message
            );

            $res['success']="Bulletin Updated Successfully";

            $res['img_path']='';

            if($cover_image)
            {
                 $bulletin = Bulletin::where('id',$id)->first();
                 $res['img_path']=$bulletin->CoverImagePath;
                //$bulletin->path = $bulletin_file_path;
            }
           
            
            return $res;
        }
        catch (Exception $e)
        {
            Log::info($e->getMessage());

        }
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
            $bulletin = Bulletin::where('id',$id)->first();
            $bulletin->delete();

            $message = 'Bulletin Deleted Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $bulletin,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_BULLETIN,
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

    public function downloadattachments(Request $request,$id)
    {
        $bulletin = Bulletin::where('id', '=', $id)->first();
        if(Gate::allows('bulletin',$bulletin))
        {

            $file=$bulletin->path;
            $path=$this->getFilePathforDownload($file,env('FILESYSTEM_DRIVER'));
            if($bulletin->type === 'week')
            {
                $name='bulletin_week-'.$bulletin->week.'_'.$bulletin->year.'.pdf';
            }
            else
            {
                $name='bulletin_month-'.$bulletin->month.'_'.$bulletin->year.'.pdf';
            }
            /*$headers = [
            'Content-Disposition' => 'attachment; filename="'. $file.'"',
            ];*/
            $headers    = [
                'Content-Disposition' => 'attachment; filename="'. $name.'"',
            ];

            $message=('Bulletin Downloaded Successfully');

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $bulletin,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DOWNLOAD_BULLETIN,
                $message
                );

            /*return response()->download($path,$file,$headers); */
            return \Response::make($path, 200, $headers);
        }
        else
        {
            abort(403);
        }
    }
}
