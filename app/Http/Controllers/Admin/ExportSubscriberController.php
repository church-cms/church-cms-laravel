<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Subscriber as SubscriberResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Models\Subscribers;
use App\Traits\Common;
use League\Csv\Writer;
use SplFileObject;
use Exception;
use DB;

/**
 * ExportSubscriberController
 *
 * Handles subscriber data export functionality.
 * Provides CSV export of mailing list subscribers for data management.
 * Supports bulk export of subscriber information and list backup.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class ExportSubscriberController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportSubscribers(Request $request)
    {
        $subscribers = Subscribers::where('church_id',Auth::user()->church_id)->get();

        $subscribers = SubscriberResource::collection($subscribers);
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        if(count($subscribers) > 0)
        {
            $csv->insertOne(['firstname','lastname','email','aff','source',]);

            foreach($subscribers as $subscriber)
            {
                $csv->insertOne([
                    $subscriber->firstname,
                    $subscriber->lastname,
                    $subscriber->email,
                    $subscriber->aff,
                    $subscriber->source,
                ]);
            }
        }
        else
        {
            $csv->insertOne(['No Records Found']);
            $csv->output('CS Subscriber Export'.date('_d-m-Y_H:i').'.csv');
        }
        $csv->output('CS Subscriber Export'.date('_d-m-Y_H:i').'.csv');
        $message=('Subscriber Details Exported Successfully');

        $ip= $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
            LOGNAME_EXPORT_SUBSCRIBER,
            $message
        );
        //\Session::put('successmessage','Member Exported Successfully');
    }
}
