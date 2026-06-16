<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\API\UserDetail as UserDetailResource;
use App\Http\Requests\ResetChangePasswordRequest;
use App\Http\Resources\API\User as UserResource;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Traits\AuthenticationProcess;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Traits\ResetPasswordProcess;
use App\Traits\SendMessageProcess;
use App\Http\Requests\OTPRequest;
use App\Events\SinglePushEvent;
use App\Models\Authentication;
use App\Mail\ChangePassword;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Hash;
use Log;
use OpenApi\Attributes as OA;   // ← add this line
class UserController extends Controller
{
    use AuthenticationProcess;
    use ResetPasswordProcess;
    use SendMessageProcess;
    #[OA\Get(
        path: '/api/v1/member/show/{id}',
        tags: ['User'],
        summary: 'Get member details',

        security: [['sanctum' => []]],

        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/UserDetailResponse',
            )
        ]
    )]
    public function show($id)
    {
        $users = User::with('userprofile')->where([['id', Auth::user()->id], ['church_id', Auth::user()->church_id]])->get();

        $users = UserDetailResource::collection($users);

        return $users;
    }

    #[OA\Post(
        path: '/api/v1/member/updatetoken',
        tags: ['User'],
        summary: 'Update the authenticated user\'s platform push token',
        operationId: '603c8c888febf5275c533656570b7a9c',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdateTokenRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/UpdateTokenResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function updatetoken(Request $request)
    {
        //
        try {
            $user = User::where([['id', Auth::id()], ['church_id', Auth::user()->church_id]])->first();

            $user->platform_token  = $request->platform_token;

            $user->save();

            $res['message'] = 'Token Updated Successfully';

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
    #[OA\Post(
        path: '/api/v1/member/changePassword',
        tags: ['User'],
        summary: 'Change Password',

        security: [['sanctum' => []]],

        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/ChangePasswordRequest'
            )
        ),

        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/ChangePasswordResponse'
            )
        ]
    )]
    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = User::where('id', Auth::id())->first();

            $hashedPassword = $user->password;
            if (Hash::check($request->oldpassword, $hashedPassword)) {
                //Change the password
                $user->password = Hash::make($request->newpassword);
                $user->is_reset  = 0;

                $user->save();

                if ($user->email != null) {
                    Mail::to($user->email)->queue(new ChangePassword($user));
                }

                $data = [];

                $data['church_id']  =   Auth::user()->church_id;
                $data['user_id']    =   $user->id;
                $data['message']    =   "Changed Password Successfully";
                $data['type']       =   'private message';

                event(new SinglePushEvent($data));

                $res['message'] = "Changed Password Successfully";
            } else {
                $res['message'] = "Change Password Failed";
            }
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    #[OA\Post(
        path: '/api/v1/password/reset',
        tags: ['User'],
        summary: 'Request a password reset OTP sent via SMS',
        operationId: 'a3e1c2d4b5f6e7a8c9d0b1e2f3c4a5d6',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/ResetPasswordRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/ResetPasswordResponse'
            )
        ]
    )]
    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $user = User::where('mobile_no', $request->mobile_no)->first();

            $user->tokens()->delete();

            $user->platform_token  = NULL;

            $user->save();

            $this->createAuthentication($user, $request);



            return response()->json([
                'success'   =>  true,
                'message'   =>  'Check sms to reset the password'
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    #[OA\Post(
        path: '/api/v1/password/store',
        tags: ['User'],
        summary: 'Verify OTP and mark the account as ready for password change',
        operationId: 'b4d6e7f8a9c0b1d2e3f4a5b6c7d8e9f0',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StorePasswordRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/StorePasswordResponse'
            ),
            new OA\Response(
                response: 302,
                description: 'Password Does Not Match'
            )
        ]
    )]
    public function storePassword(OTPRequest $request)
    {
        //
        \DB::beginTransaction();
        try {
            $user = User::where('mobile_no', request('mobile_no'))->first();
            $authentication = Authentication::where([
                ['user_id', $user->id],
                ['status', 0]
            ])->orderBy('id', 'DESC')->get();

            if ($authentication[0]['token'] === $request->password) {
                $authentication_update = Authentication::where('id', $authentication[0]['id'])->first();

                $authentication_update->status = 1;

                $authentication_update->save();


                $user = User::where('mobile_no', $request->mobile_no)->first();

                $user->is_reset  = 1;


                $user->save();

                \DB::commit();

                return response()->json([
                    'success'   =>  true,
                    'message'   =>  'Password Reset Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success'   =>  false,
                    'message'   =>  'Password Does Not Match',
                ], 302);
            }
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    public function checkReset(Request $request)
    {
        try {
            $user = User::where('mobile_no', $request->mobile_no)->first();

            return response()->json([
                'success'   =>  true,
                'is_reset'  =>  $user->is_reset
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    #[OA\Post(
        path: '/api/v1/reset/change/password',
        tags: ['User'],
        summary: 'Change password using OTP-verified reset token',
        operationId: 'f69ef19119f4f1265f8caf3460c30ffc',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/ResetChangePasswordRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/ResetChangePasswordResponse'
            )
        ]
    )]
    public function resetChangePassword(ResetChangePasswordRequest $request)
    {
        try {
            $user = User::where('mobile_no', $request->mobile_no)->first();

            $authentication = Authentication::where([
                ['user_id', $user->id],
                ['status', 1]
            ])->orderBy('id', 'DESC')->get();

            $admin = User::where([['church_id', $user->church_id], ['usergroup_id', 3]])->first();

            if ($authentication[0]['token'] === $request->oldpassword) {
                $user->tokens()->delete();

                //Change the password
                $user->password         = Hash::make($request->newpassword);
                $user->platform_token   = NULL;
                $user->is_reset         = 0;

                $user->save();

                if ($user->email != null) {
                    Mail::to($user->email)->queue(new ChangePassword($user));
                }

                // $data->mode     = 'notification';
                // $data->subject  = 'Notify Change Password';
                // $data->message  = 'Changed Password Successfully';
                // $data->attachments  = '';

                // $this->sendMessage($data, $user->church_id, $admin->email, $user, $admin);

                $res['message'] = "Changed Password Successfully";
            } else {
                $res['message'] = "Change Password Failed";
            }
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
