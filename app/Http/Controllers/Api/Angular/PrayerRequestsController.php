<?php

namespace App\Http\Controllers\Api\Angular;

use App\Http\Resources\PrayerRequest as PrayerRequestsResource;
use App\Http\Controllers\Controller;
use App\Models\Prayer;
use Illuminate\Http\Request;
use App\Models\Church;
use App\Traits\RegisterUser;
use App\Traits\Common;
use Exception;
use Log;

class PrayerRequestsController extends Controller
{
	use RegisterUser, Common;

	public function showPrayerRequests($slug)
	{
		$church = Church::where('slug', '=', $slug)->first();

		$prayers = Prayer::where('church_id', $church->id)->active()->paginate(5);

		$prayers = PrayerRequestsResource::collection($prayers);

		return $prayers;
	}

	public function store($slug, Request $request)
	{
		try {

			$church = Church::where('slug', '=', $slug)->first();
			$path = '';
			$user = $this->createGuest($request, $church->id, $path, 5);
			if ($user) {
				$create = [
					'church_id'    => $church->id,
					'user_id'      => $user->id,
					'text'         => $request->prayer_msg,
					'original_text' => $request->prayer_msg,
					'status'       => Prayer::STATUS_PENDING,
					'member_count'    => 0,
					'guest_count'     => 0,
					'anonymous_count' => 0,
				];
				$prayer_req = Prayer::create($create);
				if ($prayer_req) {
					$success = true;
				} else {
					$success = false;
				}

				return response()->json([
					'status'    =>  $success,
				], 200);
			}
		} catch (Exception $e) {
			Log::info($e->getMessage());
		}
	}
}
