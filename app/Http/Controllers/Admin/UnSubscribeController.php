<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MailingList;
use App\Models\MailinglistSubscriber;
use App\Models\Subscribers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Log;

/**
 * UnSubscribeController
 *
 * Handles unsubscribing from mailing lists.
 * Allows users to remove themselves from specific mailing lists.
 * Manages subscriber removal and list associations.
 *
 * @package App\Http\Controllers\Admin
 */
class UnSubscribeController extends Controller
{
    public function create(Request $request, $mailinglist_slug)
    {
        try {
            $mailinglist = MailingList::where('slug', $mailinglist_slug)->first();
            $email = $request->query('email');

            if (!$mailinglist || !$email) {
                return response()->view('errors.403', [], Response::HTTP_FORBIDDEN);
            }

            $subscriber = Subscribers::where('email', $email)->first();

            if (!$subscriber) {
                return response()->view('errors.403', [], Response::HTTP_FORBIDDEN);
            }

            MailinglistSubscriber::where('mailing_list_id', $mailinglist->id)
                ->where('subscribers_id', $subscriber->id)
                ->delete();

            return response('Unsubscribed successfully.', Response::HTTP_OK);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());

            return response()->view('errors.500', [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
