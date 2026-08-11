<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Resources\API\Guest\Group as GroupResource;
use App\Http\Controllers\Controller;
use App\Models\Group;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($church_id)
    {
        //
        $groups = Group::where('church_id',$church_id)->latest()->paginate(10);
         
        $groups = GroupResource::collection($groups);

        return $groups;
    }
}