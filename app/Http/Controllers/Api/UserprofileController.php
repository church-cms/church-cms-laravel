<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\API\Country as CountryResource;
use App\Http\Resources\API\State as StateResource;
use App\Http\Resources\API\City as CityResource;
use App\Http\Requests\EditUserDetailRequest;
use App\Http\Requests\EditUserProfileImgRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SiteHelper;
use Illuminate\Http\Request;
use App\Models\Userprofile;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\User;
use Exception;
use Log;
use OpenApi\Attributes as OA;


class UserprofileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    #[OA\Get(
        path: '/api/v1/member/get/country',
        tags: ['Location'],
        summary: 'Country List',
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/CountryResponse'
            )
        ]
    )]
    public function country()
    {
        //
        $country = Country::where('status', '1')->get();
        $country = CountryResource::collection($country);

        return $country;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    #[OA\Get(
        path: '/api/v1/member/get/state/{id}',
        tags: ['Location'],
        summary: 'State List',

        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],

        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/StateResponse'
            )
        ]
    )]
    public function state($id)
    {
        //
        $state = State::where('country_id', $id)->get();
        $state = StateResource::collection($state);

        return $state;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    #[OA\Get(
        path: '/api/v1/member/get/city/{id}',
        tags: ['Location'],
        summary: 'City List',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],

        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/CityResponse'
            )
        ]
    )]
    public function city($id)
    {
        //
        $city = City::where('state_id', $id)->get();
        $city = CityResource::collection($city);

        return $city;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    #[OA\Get(
        path: '/api/v1/member/get/profession',
        tags: ['User'],
        summary: 'Get profession list',
        operationId: 'daa79a4723c4aeff90d93a583451146c',
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/ProfessionResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function create()
    {
        //
        $profession = [];
        $profession['data'] = ['business', 'doctor', 'engineer', 'government_employee', 'home_maker', 'lawyer', 'pastor', 'police', 'professionals', 'self_employed', 'student', 'teacher', 'others'];

        return $profession;
    }

    #[OA\Get(
        path: '/api/v1/member/get/marriage_status',
        tags: ['User'],
        summary: 'Get marriage status list',
        operationId: 'a83a402aec4f031cf54f0f658963b1c0',
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/MarriageStatusResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function marriage_status()
    {
        $marriage_status = SiteHelper::getMarriageStatus();

        return response()->json([
            'status'            => 'success',
            'data'              => $marriage_status,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // #[OA\Post(
    //     path: '/api/v1/member/edit/{id}',

    //     security: [['sanctum' => []]],

    //     requestBody: new OA\RequestBody(
    //         required: true,
    //         content: new OA\JsonContent(
    //             ref: '#/components/schemas/EditUserDetailRequest'
    //         )
    //     ),

    //     responses: [
    //         new OA\Response(
    //             response: 200,
    //             ref: '#/components/responses/EditUserDetailResponse'
    //         )
    //     ]
    // )]

    #[OA\Post(
        path: '/api/v1/member/edit',
        tags: ['User'],
        summary: 'Edit user',

        security: [['sanctum' => []]],

        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    ref: '#/components/schemas/EditUserDetailRequest'
                )
            )
        ),

        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/EditUserDetailResponse'
            )
        ]
    )]

    public function update(EditUserDetailRequest $request)
    {
        //
        try {

            $userprofile = Userprofile::where([['user_id', Auth::id()], ['church_id', Auth::user()->church_id]])->first();
            // if($request->hasFile('avatar'))
            // {
            //   $file = $request->file('avatar');
            //   $path = \Storage::putFile('uploads/admin/member/avatar',$file);
            //   $userprofile->avatar = $path;
            // }
            #Last Use
            // if ($request->hasFile('avatar')) {

            //     $file = $request->file('avatar');

            //     $path = $file->store('uploads/admin/member/avatar', 'public');

            //     $userprofile->avatar = $path;
            // } else {
            //     $userprofile->avatar = $userprofile->avatar;
            // }

            $userprofile->firstname             = $request->firstname;
            $userprofile->lastname              = $request->lastname;
            $userprofile->birth_firstname       = $request->birth_firstname;
            $userprofile->birth_lastname        = $request->birth_lastname;
            $userprofile->gender                = $request->gender;
            $userprofile->aadhar_number         = $request->aadhar_number;
            $userprofile->date_of_birth         = date('Y-m-d', strtotime($request->date_of_birth));
            /*$userprofile->was_baptized          = $request->was_baptized;
            $userprofile->baptism_date          = $request->baptism_date;*/
            $userprofile->profession            = $request->profession;
            $userprofile->sub_occupation        = $request->sub_occupation;
            $userprofile->address               = $request->address;
            $userprofile->city_id               = $request->city;
            $userprofile->state_id              = $request->state;
            $userprofile->country_id            = $request->country;
            $userprofile->pincode               = $request->pincode;
            $userprofile->family                = $request->family;
            $userprofile->marriage_status       = $request->marriage_status;
            $userprofile->marriage_start_date   = $request->marriage_start_date;
            $userprofile->notes                 = $request->notes;

            $userprofile->save();

            return response()->json([
                'status'            => 'success',
                'message'           => 'User Details Updated Successfully',
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    #[OA\Post(
        path: '/api/v1/member/editprofileimg',
        tags: ['User'],
        summary: 'Update the profile image for a member',
        operationId: 'e3f4a5b6c7d8e9f0a1b2c3d4e5f6a7b8',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    ref: '#/components/schemas/EditProfileImgRequest'
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/EditProfileImgResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function updateprofileImg(EditUserProfileImgRequest $request)
    {
        //
        try {

            $userprofile = Userprofile::where([['user_id', Auth::id()], ['church_id', Auth::user()->church_id]])->first();
            // if($request->hasFile('avatar'))
            // {
            //   $file = $request->file('avatar');
            //   $path = \Storage::putFile('uploads/admin/member/avatar',$file);
            //   $userprofile->avatar = $path;

            // }

            if ($request->hasFile('avatar')) {

                $file = $request->file('avatar');

                $path = $file->store('uploads/admin/member/avatar', 'public');

                $userprofile->avatar = $path;
            } else {
                $userprofile->avatar = $userprofile->avatar;
            }

            $userprofile->save();

            return response()->json([
                'status'            => 'success',
                'message'           => 'User Profile Image Updated Successfully',
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
