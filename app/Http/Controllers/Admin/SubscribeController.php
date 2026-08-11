<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\MailingList;
use App\Models\Subscribers;
use Exception;
use App\Http\Requests\SubscriberRequest;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Traits\SubscriberProcess;
use App\Events\SubscriberConfirmEvent;
use App\Traits\EmailQueueProcess;
use Log;

/**
 * SubscribeController
 *
 * Handles newsletter subscriber subscription management.
 * Allows users to subscribe to specific mailing lists.
 * Manages subscriber confirmation and email queue processing.
 *
 * @package App\Http\Controllers\Admin
 * @uses SubscriberProcess Trait for subscriber management
 * @uses EmailQueueProcess Trait for email queuing
 */
class SubscribeController extends Controller
{
    use SubscriberProcess, EmailQueueProcess;


    public function create($mailinglist_slug)
    {


        try {
            $mailinglist = MailingList::where('slug', '=', $mailinglist_slug)->first();

            if (count($mailinglist) > 0) {
                return view('admin.subscriber.create_subscriber', [
                    'mailinglist' => $mailinglist,
                ]);
            } else {
                return view('errors.403');
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }


    public function store(SubscriberRequest $request) //Subscriber
    {

        \DB::beginTransaction();
        try {

            $slug = \Request::segment('3');

            $church_id = Auth::user()->church_id;

            $this->createSubscriberAttachToMailingList($slug, $request, $church_id);

            \DB::commit();
            \Session::put('successmessage', 'Subscribed Successfully');
        } catch (Exception $e) {
            \DB::rollBack();
            \Session::put('failmessage', 'Try after sometime');
            Log::info($e->getMessage());
        }
        return Redirect::back();
    }

    public function confirm(Request $request)
    {
        $email = $request->email;
        $subscriber = Subscribers::where('email', $email)->first();
        if (count($subscriber) > 0) {
            if ($subscriber->email_verified_at === '') {
                $MailinglistSubscriber = MailinglistSubscriber::where('subscriber_id', $subscriber->id)->first();

                MailinglistSubscriber::where('subscriber_id', $subscriber->id)->update(['status' => 1]);
                $subscriber->email_verified_at = Carbon::now();
                $subscriber->is_active = 1;
                $subscriber->save();
                $data = array();


                event(new SubscriberConfirmEvent($MailinglistSubscriber->mailinglist_id, $MailinglistSubscriber->subscriber_id));
            }

            return view('admin.subscriber.confirm');
        } else {
            return view('errors.403');
        }
    }
}
