<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ActivityLog as ActivityLogResource;
use App\Http\Resources\UserDetail as UserDetailResource;
use App\Http\Resources\SendMail as SendMailResource;
use App\Http\Resources\Group as GroupResource;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\Userprofile;
use App\Models\NewsLetter;
use App\Models\GroupLink;
use App\Models\SendMail;
use App\Models\Group;
use App\Models\User;

/**
 * MemberController
 *
 * Manages church member operations including profile viewing, family relationship navigation,
 * and member hierarchy management. Handles family tree visualization and member details.
 * Supports family relationship tracking through self-referential User hierarchy (parent/child/partner).
 *
 * @package App\Http\Controllers\Admin
 * @see User Model for family relationship functionality
 * @see Userprofile Model for extended member information
 */
class MemberController extends Controller
{
    //

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showDetails($name)
    {
        //
        $users = User::with('userprofile')->where('name', $name)->get();
        $users = UserDetailResource::collection($users);

        return $users;
    }

    public function showFamily($name)
    {
        $user = User::with('userprofile')->where('name', $name)->first();

        $array = [];

        if(count($user->members) === 0)
        {
            $array['members'][1]['name']        = $user->refer->name;
            $array['members'][1]['fullname']    = $user->refer->FullName;
            $array['members'][1]['relation']    = $user->userprofile->relation === 'partner' ? 'Partner':ucfirst($user->userprofile->relation);
            $array['members'][1]['avatar']      = $user->refer->userprofile->AvatarPath;

            $i = 2;
            foreach ($user->refer->members as $member)
            {
                if($member->name != $name)
                {
                    $array['members'][$i]['name']        = $member->name;
                    $array['members'][$i]['fullname']    = $member->FullName;
                    $array['members'][$i]['relation']    = $member->userprofile->relation === 'partner' ? 'Partner':ucfirst($member->userprofile->relation);
                    $array['members'][$i]['avatar']      = $member->userprofile->AvatarPath;
                    $i++;
                }
            }
        }
        else
        {
            $i = 1;
            foreach ($user->members as $member)
            {
                if($member->name != $name)
                {
                    $array['members'][$i]['name']        = $member->name;
                    $array['members'][$i]['fullname']    = $member->FullName;
                    $array['members'][$i]['relation']    = $member->userprofile->relation === 'partner' ? 'Partner':ucfirst($member->userprofile->relation);
                    $array['members'][$i]['avatar']      = $member->userprofile->AvatarPath;
                    $i++;
                }
            }
        }

        return $array;
    }

    public function familytree($name)
    {
        $users = User::where('name', $name)->first();
        $user=$this->mytree($name);
        /* $referuser=$user[0]->refer;
        if($referuser===null){
            $user=$this->mytree($name);
        }
        else
        {
            $user=$this->mytree($referuser->name);
        }*/

        if($users->father->isNotEmpty())
        {
            $user=$this->myparent($users,$user);
        }
        elseif($users->mother->isNotEmpty())
        {
            $user=$this->mother($users,$user);
        }
        $user = json_decode($user);

        return $user;
    }

    public function mother($son,$family)
    {
        $user = User::where('name', $son->mother[0]->name)->get();
        $user = $user->map(function( $user, $key) use ($family,$son)  {
            if($user->userprofile->relation === 'partner')
            {
                $relation = 'Partner';
            }
            else
            {
                $relation = ucfirst($user->userprofile->relation);
            }
            $userData = [
                'id'        =>  $user->id,
                'name'      =>  $user->FullName."\n"."(".$relation.")" ,
                'image_url' =>  $user->userprofile->AvatarPath ,
                'children'  =>  $family,
            ];
            return $userData;
        });
        return $user;
    }

    public function myparent($son,$family)
    {
        $user = User::where('name', $son->father[0]->name)->get();
        $user = $user->map(function( $user, $key) use ($family,$son)  {
            if($son->mother->isEmpty())
            {
               $partner='';
            }
            else
            {
                if($son->mother[0]->userprofile->relation === 'partner')
                {
                    $relation = 'Partner';
                }
                else
                {
                    $relation = ucfirst($son->mother[0]->userprofile->relation);
                }
                $partner=[
                    'name'      =>  $son->mother[0]->userprofile->firstname."\n"."(".$relation.")",
                    'image_url' =>  $son->mother[0]->userprofile->AvatarPath
                ];
            }
            if($user->userprofile->relation === 'partner')
            {
                $relations = 'Partner';
            }
            else
            {
                $relations = ucfirst($user->userprofile->relation);
            }
            $userData = [
                'id'        =>  $user->id,
                'name'      =>  $user->FullName."\n"."(".$relations.")" ,
                'image_url' =>  $user->userprofile->AvatarPath ,
                'mate'      =>  $partner,
                'children'  =>  $family,
            ];

            return $userData;
        });
        return $user;
    }

    public function mytree($name)
    {
        $user = User::where('name', $name)->get();
        $user = $user->map(function( $user, $key) {
            if($user->children->isEmpty())
            {
                if($user->refer != null)
                {
                    $child = $this->myfunction($user->refer->children);
                }
                else
                {
                    $child = '';
                }
            }
            else
            {
                $child=$this->myfunction($user->children);
            }

            if($user->partner->isEmpty())
            {
                if($user->refer != null)
                {
                    if($user->userprofile->relation != null)
                    {
                        if($user->userprofile->relation === 'partner')
                        {
                            $relation1 = 'Partner';
                        }
                        else
                        {
                            $relation1 = ucfirst($user->userprofile->relation);
                        }
                    }
                    else
                    {
                        if($user->userprofile->relation === 'partner')
                        {
                            $relation1 = 'Partner';
                        }
                        else
                        {
                            $relation1 = ucfirst($user->userprofile->relation);
                        }
                    }
                    $partner=[
                        'name'      =>  $user->refer->FullName."\n"."(".$relation1.")",
                        'image_url' =>  $user->refer->userprofile->AvatarPath
                    ];
                }
                else
                {
                    $partner = '';
                }
            }
            else
            {
                if($user->partner[0]->userprofile->relation === 'partner')
                {
                    $relation = 'Partner';
                }
                else
                {
                    $relation = ucfirst($user->partner[0]->userprofile->relation);
                }
                $partner=[
                    'name'      =>  $user->partner[0]->FullName."\n"."(".$relation.")",
                    'image_url' =>  $user->partner[0]->userprofile->AvatarPath
                ];
            }
            if($user->userprofile->relation != null)
            {
                if($user->userprofile->relation === 'partner')
                {
                    $relations = 'Partner';
                }
                else
                {
                    $relations = ucfirst($user->userprofile->relation);
                }
            }
            else
            {
                if($user->partner[0]->userprofile->relation === 'partner')
                {
                    $relations = 'Partner';
                }
                else
                {
                    $relations = ucfirst($user->partner[0]->userprofile->relation);
                }
            }

            $userData = [
                'id'        =>  $user->id,
                'name'      =>  $user->FullName."\n"."(".$relations.")",
                'image_url' =>  $user->userprofile->AvatarPath ,
                'mate'      =>  $partner,
                'children'  =>  $child,
            ];

            return $userData;
        });

        return $user;
    }

    public function myfunction($children)
    {
        $childrenData = $children->map(function( $children, $key){
            if($children->partner->isEmpty())
            {
                $partner='';
            }
            else
            {
                if($children->partner[0]->userprofile->relation === 'partner')
                {
                    $relation = 'Partner';
                }
                else
                {
                    $relation = ucfirst($children->partner[0]->userprofile->relation);
                }
                $partner=[
                    'name'      =>  $children->partner[0]->FullName."\n"."(".$relation.")",
                    'image_url' =>  $children->partner[0]->userprofile->AvatarPath
                ];
            }
            if($children->userprofile->relation === 'partner')
            {
                $relations = 'Partner';
            }
            else
            {
                $relations = ucfirst($children->userprofile->relation);
            }
            $childrens=[
                'id'        =>  $children->id,
                'name'      =>  $children->FullName."\n"."(".$relations.")",
                'mate'      =>  $partner,
                'image_url' =>  $children->userprofile->AvatarPath ,
                'children'  =>  $this->myfunction($children->children),
            ];
            if($childrens['children']->isEmpty())
            {
                $childrens['children']='';
            }
            return $childrens;
        });
        return $childrenData;
    }

    public function showActivity($name)
    {
        //
        $user = User::with('userprofile')->where('name', $name)->first();
        if(Gate::allows('member',$user))
        {
            $activitylog = ActivityLog::where('subject_id',$user->userprofile->id)->paginate(5);
            $activitylog = ActivityLogResource::collection($activitylog);

            return $activitylog;
        }
        else
        {
            abort(403);
        }
    }

    public function showGroups($name)
    {
        //
        $user = User::with('userprofile')->where('name', $name)->first();
        if(Gate::allows('member',$user))
        {
            $grouplinks = GroupLink::where('user_id',$user->id)->get();

            $group = GroupResource::collection($grouplinks);

            return $group;
        }
        else
        {
            abort(403);
        }
    }

    public function showMessages($name)
    {
        //
        $user = User::with('userprofile')->where('name', $name)->first();
        if(Gate::allows('member',$user))
        {
            $messages = SendMail::where('user_id',$user->id)->orderBy('executed_at','DESC')->paginate(5);

            $messages = SendMailResource::collection($messages);

            return $messages;
        }
        else
        {
            abort(403);
        }
    }

    public function show($name)
    {
        //
        $user = User::with('userprofile')->where('name', $name)->first();

        if(Gate::allows('member',$user))
        {
            $newsletter = NewsLetter::where('email',$user->email)->first();
            if($newsletter != null)
            {
                $status = $newsletter->status;
            }
            else
            {
                $status = 0;
            }

            if($_SERVER['HTTP_REFERER'] != null)
            {
                $prev_url = $_SERVER['HTTP_REFERER'];
            }
            else
            {
                $prev_url = url('/admin/members');
            }
            return view('/admin/member/show',['user'=>$user , 'status' => $status , 'prev_url' => $prev_url , 'request' => request()]);
        }
        else
        {
            abort(403);
        }
    }
}
