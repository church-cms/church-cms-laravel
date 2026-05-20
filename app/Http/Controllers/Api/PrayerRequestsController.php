<?php

namespace App\Http\Controllers\Api;

use App\Events\Notification\SingleNotificationEvent;
use App\Http\Resources\API\PrayerRequestUser as PrayerRequestUserResource;
use App\Http\Resources\API\PrayerRequest as PrayerRequestResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitPrayerRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Prayer;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use Exception;
use Log;
use App\Http\Resources\API\PrayerCategory as PrayerCategoryResource;
use App\Models\PrayerCategory;
class PrayerRequestsController extends Controller
{
    /**
     * List active prayers for the public board (excluding the current user's own prayers).
     */
    public function index()
    {
        $prayers = Prayer::forChurch(Auth::user()->church_id)
            ->active()
            ->forPublicBoard()
            ->where('user_id', '!=', Auth::id())
            ->get();

        return PrayerRequestResource::collection($prayers);
    }

    /**
     * Submit a new prayer request (creates a PENDING prayer).
     */
    public function store(SubmitPrayerRequest $request)
    {
        try {
            $prayer = new Prayer;
            $prayer->church_id    = Auth::user()->church_id;
            $prayer->user_id      = Auth::id();
            $prayer->category_id  = $request->category_id;
            $prayer->text         = $request->description;
            $prayer->original_text = $request->description;
            $prayer->status       = Prayer::STATUS_PENDING;
            $prayer->save();

            activity()
                ->performedOn($prayer)
                ->causedBy(Auth::user())
                ->useLog('prayer')
                ->log('Prayer request submitted via app');

            $array = [];
            $admin = SiteHelper::getAdmin(Auth::user()->church_id);
            $array['user']    = $admin;
            $array['details'] = 'New Prayer Request Received';
            event(new SingleNotificationEvent($array));

            return ['message' => 'Prayer request submitted successfully'];
        } catch (Exception $e) {
            Log::error('PrayerRequestsController@store: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to submit prayer request'], 500);
        }
    }

    /**
     * List the authenticated user's own prayers.
     */
    public function show()
    {
        $prayers = Prayer::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return PrayerRequestUserResource::collection($prayers);
    }

    public function prayerCategory()
    {
        $prayercatlist = PrayerCategory::where('is_active', 1)
            ->orderBy('sort_order','ASC')
            ->get();

        return PrayerCategoryResource::collection($prayercatlist);
    }

}
