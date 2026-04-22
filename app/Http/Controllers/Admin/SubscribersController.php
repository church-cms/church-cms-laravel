<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Subscribers as SubscribersResource;
use App\Http\Requests\SubscriberUpdateRequest;
use App\Http\Requests\SubscriberAddRequest;
use App\Models\MailinglistSubscriber;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SubscribersImport;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Models\Subscribers;
use App\Models\MailingList;
use League\Csv\Writer;
use App\Traits\Common;
use Exception;
use Log;

/**
 * SubscribersController
 *
 * Manages newsletter subscribers and email distribution lists.
 * Handles subscriber addition, updates, deletion, and bulk import/export operations.
 * Supports CSV file uploads for bulk subscriber management and mailing list associations.
 * Integrates with email queue and campaign management systems.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for file utilities
 */
class SubscribersController extends Controller
{
    use LogActivity;
    use Common;

    public function index()
    {
        return view('/admin/subscriber/index');
    }

    public function list()
    {
        $subscribers = Subscribers::where('church_id', Auth::user()->church_id)->get();

        return SubscribersResource::collection($subscribers);
    }

    public function create()
    {
        return view('/admin/subscriber/create');
    }

    public function store(SubscriberAddRequest $request)
    {
        try {
            $subscriber = new Subscribers;

            $subscriber->church_id  = Auth::user()->church_id;
            $subscriber->firstname  = $request->firstname;
            $subscriber->lastname   = $request->lastname;
            $subscriber->email      = $request->email;
            $subscriber->aff        = $request->aff;
            $subscriber->source     = $request->source;
            $subscriber->is_active  = $request->is_active;

            $subscriber->save();

            $message = 'Subscriber Created Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $subscriber,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_SUBSCRIBER,
                $message
            );

            $res['success'] = $message;
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function edit($id)
    {
        $subscriber = Subscribers::where('id', $id)->first();

        return view('/admin/subscriber/edit', ['subscriber' => $subscriber]);
    }

    public function update(SubscriberUpdateRequest $request, $id)
    {
        try {
            $subscriber = Subscribers::where('id', $id)->first();

            $subscriber->firstname  = $request->firstname;
            $subscriber->lastname   = $request->lastname;
            $subscriber->email      = $request->email;
            $subscriber->aff        = $request->aff;
            $subscriber->source     = $request->source;
            $subscriber->is_active  = $request->is_active;

            $subscriber->save();

            $message = 'Subscriber Updated Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $subscriber,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_SUBSCRIBER,
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
        $subscriber = Subscribers::where([['id', $id], ['church_id', Auth::user()->church_id]])->first();

        $mailinglistSubscriber = MailinglistSubscriber::where('subscribers_id', $id)->pluck('mailing_list_id')->toArray();

        $maillists = MailingList::whereIn('id', $mailinglistSubscriber)->get();

        if ($_SERVER['HTTP_REFERER'] != null) {
            $prev_url = $_SERVER['HTTP_REFERER'];
        } else {
            $prev_url = url('/admin/subscribers');
        }

        return view('/admin/subscriber/show', ['subscriber' => $subscriber, 'maillists' => $maillists, 'prev_url' => $prev_url]);
    }

    public function showDetails($id)
    {
        $subscriber = Subscribers::where('id', $id)->first();

        $array = [];

        $array['id']                =  $subscriber->id;
        $array['firstname']         =  $subscriber->firstname;
        $array['lastname']          =  $subscriber->lastname;
        $array['email']             =  $subscriber->email;
        $array['aff']               =  $subscriber->aff;
        $array['source']            =  $subscriber->source;
        $array['is_active']         =  $subscriber->is_active;
        $array['attach_to_list']    =  $subscriber->mailinglist[0]['name'];

        return $array;
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
            $subscriber = Subscribers::where('id', $id)->first();

            $subscriber->delete();

            $message = 'Subscriber Deleted Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $subscriber,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_SUBSCRIBER,
                $message
            );

            $res['success'] = $message;
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function import()
    {
        //
        return view('admin/subscriber/import');
    }

    public function downloadFormat(Request $request)
    {
        try {
            $csv = Writer::createFromFileObject(new \SplTempFileObject());
            $csv->insertOne(['firstname', 'lastname', 'email', 'aff', 'source', 'active']);

            $csv->insertOne([
                '(firstname)',
                '(lastname)',
                '(email)',
                '(aff)',
                '(source)',
                '(0,1)',
                'Delete this entire row before importing'
            ]);

            $csv->output('Subscriber Format' . date('_d-m-Y_H:i') . '.csv');

            $message = 'Downloaded Sample Format File Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                Auth::user(),
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DOWNLOAD_SUBSCRIBER_SAMPLE_FORMAT,
                $message
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function importSubscribers(Request $request)
    {
        try {
            Excel::import(new SubscribersImport, $request->file('import_file'));
            $subscriberCount = \Session::get('insertedcount');

            if ($subscriberCount > 0) {
                $message = trans('messages.import_success_msg', ['module' => 'Subscribers']);

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    Auth::user(),
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_IMPORT_SUBSCRIBER,
                    $message
                );
                return back()->with('successmessage', $subscriberCount . ' Subscribers Imported Successfully');
            } else {
                return back()->with('failmessage', 'No Subscribers Imported');
            }
            $request->session()->forget('insertedcount');
            //\Session::forget('subscriberCount');
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
