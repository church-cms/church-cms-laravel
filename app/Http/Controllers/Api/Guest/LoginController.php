<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Requests\Api\Guest\GuestAddRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Traits\RegisterUser;
use App\Models\Userprofile;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\Church;
use App\Models\User;
use Exception;
use Log;

class LoginController extends Controller
{
    use RegisterUser;
    use LogActivity;
    use Common;

    public $successStatus = 200;

    public $failStatus = 302;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        try {
            if (Auth::attempt(['mobile_no' => request('email'), 'password' => request('password')])) {
                $user = Auth::user();

                $userprofile = Userprofile::where('user_id', $user->id)->first();
                if ($userprofile->status === 'active') {
                    $token = $user->createToken("churchcms")->plainTextToken;


                    $user = User::where([['id', $user->id], ['church_id', $user->church_id]])->first();

                    $user->platform_token = $request->platform_token;

                    $user->save();

                    return response()->json([
                        'status'            => 'success',
                        'token'             =>  $token,
                        'id'                =>  $user->id,
                        'church_id'         =>  $user->church_id,
                        'user_email'        =>  $user->email,
                        'user_name'         =>  $user->name,
                        'user_fullname'     =>  $user->FullName,
                        'membership_type'   =>  $user->userprofile->membership_type,
                    ], $this->successStatus);
                }
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            if (Auth::check()) {
                Auth()->user()->tokens()->delete();
            }

            $user = User::where('id', Auth::id())->first();

            $user->platform_token  = NULL;

            $user->save();

            return response()->json([
                'success'   =>  true,
                'message'   =>  'Logged out successfully'
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $array['data'][0]['id']     = 'male';
        $array['data'][0]['name']   = 'Male';
        $array['data'][1]['id']     = 'female';
        $array['data'][1]['name']   = 'Female';

        return $array;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GuestAddRequest $request)
    {
        //
        try {
            $church = Church::where('id', $request->church_id)->first();
            $path = '';
            $user = $this->createGuest($request, $church->id, $path, 5);

            $message = 'Guest Added Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $user,
                1,
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_GUEST,
                $message
            );

            return response()->json([
                'success'   =>  true,
                'message'   =>  'You Are Added To This Church Successfully',
                'user_id'   =>  $user->id,
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
