<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
//use App\Http\Requests\LoginRequest;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Http\Request;
use App\Models\Userprofile;
use App\Models\User;
use App\Token;
use Exception;
use Log;
use OpenApi\Attributes as OA;   // ← add this line
use Illuminate\Support\Facades\Validator;

/**
 * LoginController
 *
 * Handles user authentication and login via API.
 * Processes user login requests and returns authentication tokens.
 *
 * @package App\Http\Controllers\Api
 */
class LoginController extends Controller
{
    public $successStatus = 200;

    public $failStatus = 302;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    #[OA\Post(
        path: '/api/login',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/LoginRequest'
            )
        ),

        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/LoginResponse'
            )
        ]
    )]

    public function login(LoginRequest $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'device_id' => 'required|teacher_logoutdevice_id',
        // ], [
        //     'teacher_logoutdevice_id' => 'Your account is currently logged onto another device. Please log out of the other device or contact your administrator',
        // ])->validate();

        try {

            if (Auth::attempt(['mobile_no' => request('email'), 'password' => request('password')])) {
                $user = Auth::user();
                $user->tokens()->delete();

                $userprofile = Userprofile::where('user_id', $user->id)->first();
                if ($userprofile->status === 'active') {
                    $token = $user->createToken("churchcms")->plainTextToken;


                    $user = User::where([['id', $user->id], ['church_id', $user->church_id]])->first();

                    $user->platform_token = $request->platform_token;
                    $user->device_id = $request->device_id;

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
                        'is_reset'          =>  $user->is_reset,
                    ], $this->successStatus);
                }
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    #[OA\Post(
        path: '/api/logout',
        summary: 'Logout',
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/LogoutResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]

    public function logout(Request $request)
    {
        try {
            if (Auth::check()) {
                Auth()->user()->tokens()->delete();
            }

            $user = User::where('id', Auth::id())->first();

            $user->platform_token  = NULL;
            $user->device_id  = NULL;

            $user->save();

            return response()->json([
                'success'   =>  true,
                'message'   =>  'Logged out successfully'
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    #[OA\Post(
        path: '/api/logout/devices',
        summary: 'Logout All Devices',

        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/LogoutAllRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/LogoutAllResponse'
            )
        ],

    )]
    public function logoutDevices(Request $request)
    {


        try {
            $user = User::where([['mobile_no', $request->email], ['device_id', '!=', null], ['usergroup_id', 5]])->first();

            if ($user != null) {

                $user->tokens()->delete();

                $user->platform_token  = NULL;

                $user->device_id = NULL;

                $user->save();

                return response()->json([
                    'success'   =>  true,
                    'message'   =>  'Logged out from all devices'
                ], 200);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }
}
