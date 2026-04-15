<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GroupUpdateRequest;
use App\Http\Requests\GroupAddRequest;
use App\Http\Requests\SendMailRequest;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\SendMessageProcess;
use App\Models\PermissionUser;
use App\Models\GroupCategory;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Models\GroupLink;
use App\Models\SendMail;
use App\Traits\Common;
use App\Models\Group;
use App\Models\User;
use Exception;
use Log;

/**
 * GroupsController
 *
 * Manages community groups (ministries, departments, etc.) within the church.
 * Handles group creation, member management, group messaging, and group categorization.
 * Supports permission-based group access and SendMail messaging to group members.
 * Integrates with subscription-based group feature limits.
 *
 * @package App\Http\Controllers\Admin
 * @uses SendMessageProcess Trait for group messaging functionality
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class GroupsController extends Controller
{
    use SendMessageProcess;
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $groups = Group::where('church_id',Auth::user()->church_id);
        if(\Request::getQueryString() != null)
        {
            if($request->search != null)
            {
                $groups = $groups->where('name','LIKE','%'.$request->search.'%')->orWhere('description','LIKE','%'.$request->search.'%');
            }
        }
        $groups = $groups->get();
       

        return view('/admin/groups/index',['groups' => $groups ]);
    }

    public function getData()
    {
        $array =[];
        $group_category = GroupCategory::get();
        $array['categorylist'] = $group_category;

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
        $count = Group::where('church_id',Auth::user()->church_id)->count();

        return view('/admin/groups/create',['count' => $count ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupAddRequest $request)
    {
        //
        try
        {
            $count = Group::count();
            $id = $count+'1';
            $church_id  = Auth::user()->church_id;
            $created_by = Auth::id();

            $group              = new Group;

            $group->church_id   = $church_id;
            $group->category_id = $request->category;
            $group->group_type  = $request->group_type;
            $group->name        = $request->name;
            $group->description = $request->description;

            $cover_image = $request->file('cover_image');
            if($cover_image)
                {
                    $path = $this->uploadFile('uploads/admin/groups/cover_image/'.$church_id.'/'.$id,$cover_image);
                    $group->cover_image = $path;
                }

            $group->created_by = $created_by;

            $group->save();

            $message=('Group Created Successfully');

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $group,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_GROUP,
                $message
                );

            $res['success']="Group Created Successfully";
            return $res;
        }
        catch(Exception $e)
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
    public function show($id)
    {
        //
        $group = Group::where('id',$id)->first();
        if(Gate::allows('group',$group))
        {

            $grouplinks = GroupLink::where([['group_id',$group->id],['church_id',$group->church_id]])->paginate(3);
            $memberCount = GroupLink::where([['group_id',$group->id],['church_id',$group->church_id]])->count();

            return view('/admin/groups/show',['group' => $group , 'grouplinks' => $grouplinks , 'memberCount' => $memberCount]);
        }
        else
        {
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $group = Group::where('id',$id)->first();
        if(Gate::allows('group',$group))
        {
            $group_category = GroupCategory::get();

            return view('/admin/groups/edit',['group' => $group , 'group_category' => $group_category]);
        }
        else
        {
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GroupUpdateRequest $request, $id)
    {
        //
        try
        {
            $church_id  = Auth::user()->church_id;
            $created_by = Auth::id();

            $group = Group::where([['id',$id],['church_id',$church_id]])->first();

            $group->church_id   = $church_id;
            $group->category_id = $request->category;
            $group->group_type  = $request->group_type;
            $group->name        = $request->name;
            $group->description = $request->description;

            if($request->hasFile('cover_image'))
            {
                $cover_image = $request->file('cover_image');
                $path = $this->uploadFile('uploads/admin/groups/cover_image/'.$church_id.'/'.$id,$cover_image);
                $group->cover_image = $path;
            }
            else
            {
                $group->cover_image = $group->cover_image;
            }
            $group->created_by = $created_by;

            $group->save();


            $message=('Group Details Updated Successfully');

                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    $group,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_EDIT_GROUP,
                    $message
                    );

            return redirect()->back()->with(['successmessage' => 'Group Details Updated Successfully']);
        }
        catch(Exception $e)
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
            $group = Group::where('id',$id)->first();
            if(Gate::allows('group',$group))
            {
                $groupMembers = GroupLink::where('group_id',$group->id)->get();
                foreach($groupMembers as $groupMember)
                {
                    $permissions = PermissionUser::where('user_id',$groupMember->user_id)->get();
                    foreach($permissions as $permission)
                    {
                        $permission->delete();
                    }
                    $groupMember->delete();
                }
                $group->delete();

                $message=('Group Deleted Successfully');

                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    $group,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_DELETE_GROUP,
                    $message
                );

                return redirect('/admin/groups')->with('successmessage','Group Deleted Successfully');
            }
            else
            {
                abort(403);
            }

        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function message(SendMailRequest $request , $id)
    {
        try
        {
            $grouplinks = Grouplink::where([['group_id',$id],['church_id',Auth::user()->church_id]])->get();

            foreach($grouplinks as $grouplink)
            {
                $user = User::where('id',$grouplink->user_id)->first();
                $group = Group::where('id',$grouplink->group_id)->first();

                $request->entity_id     = $grouplink->group_id;
                $request->entity_name   = get_class($group);

                $this->sendMessage($request , Auth::user()->church_id , Auth::user()->email , $user , Auth::user());
            }

            $res['success'] = 'Message Sent Successfully to Group Members';
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function messageList(Request $request,$id)
    {
        $messages = SendMail::where([['entity_id',$id],['entity_name','=','App\Models\Group'],['church_id',Auth::user()->church_id]])->orderBy('executed_at','desc');

        if($request->mode!= '')
        {
            $messages = $messages->where('mode',$request->mode);
        }

        $messages = $messages->paginate(10);

        return view('/admin/groups/messages', ['messages' => $messages , 'id' => $id]);
    }
}
