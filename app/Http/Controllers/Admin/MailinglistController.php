<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Mailinglist as MailinglistResource;
use App\Http\Requests\MailingListRequest;
use App\Models\MailinglistSubscriber;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\MailingList;
use App\Models\Subscribers;
use App\Traits\LogActivity;
use App\Traits\Common;
use Exception;
use Log;

/**
 * MailinglistController
 *
 * Manages email mailing lists for church communication.
 * Handles mailing list creation, subscriber management, and list-to-campaign associations.
 * Supports linking mailing lists to events and managing subscriber relationships.
 * Integrates with campaigns and newsletter distribution.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class MailinglistController extends Controller
{

    use LogActivity;
    use Common;

    public function index()
    {
        return view('/admin/mailinglist/index');
    }

    public function list()
    {
        $mailinglists = MailingList::where('church_id', Auth::user()->church_id)->get();

        return MailinglistResource::collection($mailinglists);
    }

    public function create()
    {
        return view('/admin/mailinglist/create');
    }

    public function store(MailingListRequest $request)
    {
        try {
            $mailinglist = new MailingList;

            $mailinglist->church_id    = Auth::user()->church_id;
            $mailinglist->name         = $request->name;
            $mailinglist->description  = $request->description;
            $mailinglist->is_published = $request->is_published;
            $mailinglist->slug         = \Str::slug($request->name);

            $mailinglist->save();

            $message = 'Mailinglist Created Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $mailinglist,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_MAILINGLIST,
                $message
            );

            $res['success'] = $message;
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function editList($id)
    {
        $mailinglist = MailingList::where('id', $id)->first();
        $array = [];

        $array['id']            = $mailinglist->id;
        $array['name']          = $mailinglist->name;
        $array['description']   = $mailinglist->description;
        $array['is_published']  = $mailinglist->is_published;

        return $array;
    }

    public function edit($id)
    {
        $mailinglist = MailingList::where('id', $id)->first();

        return view('/admin/mailinglist/edit', ['mailinglist' => $mailinglist]);
    }

    public function update(Request $request, $id) //mailinglistUpdate
    {
        try {
            $mailinglist = MailingList::where('id', $id)->first();

            $mailinglist->name          = $request->name;
            $mailinglist->description   = $request->description;
            $mailinglist->is_published  = $request->is_published;
            $mailinglist->slug          = \Str::slug($request->name);

            $mailinglist->save();

            $message = 'Mailinglist Updated Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $mailinglist,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_MAILINGLIST,
                $message
            );

            $res['success'] = $message;
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function show($id)
    {
        $mailinglist = MailingList::where([['id', $id], ['church_id', Auth::user()->church_id]])->first();

        $maillistsubscriber = MailinglistSubscriber::where('mailing_list_id', $id)->pluck('subscribers_id')->toArray();

        $subscribers = Subscribers::whereIn('id', $maillistsubscriber)->paginate(5);

        if ($_SERVER['HTTP_REFERER'] != null) {
            $prev_url = $_SERVER['HTTP_REFERER'];
        } else {
            $prev_url = url('/admin/mailinglists');
        }

        return view('admin.mailinglist.show', ['mailinglist' => $mailinglist, 'subscribers' => $subscribers, 'prev_url' => $prev_url]);
    }

    public function view($id)
    {
        $mailinglist = MailingList::where('id', '!=', $id)->get();

        $mailinglist = MailinglistResource::collection($mailinglist);

        return $mailinglist;
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
        try {
            $mailinglist = MailingList::where('id', $id)->first();

            $mailinglist->delete();

            $message = 'Mailinglist Deleted Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $mailinglist,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_MAILINGLIST,
                $message
            );

            $res['success'] = $message;
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function detachsubscriber($subscriber_id, $mailinglist_id)
    {
        try {
            $maillistsubscriber = MailinglistSubscriber::where('subscribers_id', $subscriber_id)->where('mailing_list_id', $mailinglist_id)->first();

            $maillistsubscriber->delete();

            $message = 'Subscriber Deleted From Mailing List Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $maillistsubscriber,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_SUBSCRIBER_MAILINGLIST_ATTACHMENT,
                $message
            );

            $res['success'] = $message;
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
