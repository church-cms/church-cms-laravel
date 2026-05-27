<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[
    OA\Info(
        version: '1.0.0',
        title: 'Church CMS API'
    ),

    OA\SecurityScheme(
        securityScheme: 'sanctum',
        type: 'http',
        scheme: 'bearer'
    ),

    OA\Schema(
        schema: 'CountryResource',
        properties: [
            new OA\Property(property: 'id', type: 'integer'),
            new OA\Property(property: 'name', type: 'string'),
            new OA\Property(property: 'status', type: 'integer'),
            new OA\Property(property: 'short_name', type: 'string'),
        ]
    ),

    OA\Response(
        response: 'CountryResponse',
        description: 'Country List',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/CountryResource'
            )
        )
    ),

    OA\Schema(
        schema: 'StateResource',
        properties: [
            new OA\Property(property: 'id', type: 'integer'),
            new OA\Property(property: 'country_id', type: 'integer'),
            new OA\Property(property: 'name', type: 'string'),
            new OA\Property(property: 'status', type: 'integer'),
        ]
    ),
    OA\Response(
        response: 'StateResponse',
        description: 'State List',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/StateResource'
            )
        )
    ),

    OA\Schema(
        schema: 'CityResource',
        properties: [
            new OA\Property(property: 'id', type: 'integer'),
            new OA\Property(property: 'country_id', type: 'integer'),
            new OA\Property(property: 'state_id', type: 'integer'),
            new OA\Property(property: 'name', type: 'string'),
            new OA\Property(property: 'status', type: 'integer'),

        ]
    ),
    OA\Response(
        response: 'CityResponse',
        description: 'City List',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/CityResource'
            )
        )
    ),

    OA\Schema(
        schema: 'ChangePasswordRequest',
        required: ['oldpassword', 'newpassword'],
        properties: [
            new OA\Property(property: 'oldpassword', type: 'string'),
            new OA\Property(property: 'newpassword', type: 'string'),
        ]
    ),
    OA\Response(
        response: 'ChangePasswordResponse',
        description: 'Password Changed',
        content: new OA\JsonContent(
            example: [
                'message' => 'Changed Password Successfully'
            ]
        )
    ),

    OA\Schema(
        schema: 'UserDetailResource',
        properties: [

            new OA\Property(property: 'church_name', type: 'string'),
            new OA\Property(property: 'user_id', type: 'integer'),

            new OA\Property(property: 'firstname', type: 'string'),
            new OA\Property(property: 'lastname', type: 'string'),

            new OA\Property(property: 'birth_firstname', type: 'string'),
            new OA\Property(property: 'birth_lastname', type: 'string'),

            new OA\Property(property: 'gender', type: 'string'),
            new OA\Property(property: 'date_of_birth', type: 'string'),

            new OA\Property(property: 'profession', type: 'string'),
            new OA\Property(property: 'sub_occupation', type: 'string'),

            new OA\Property(property: 'address', type: 'string'),

            new OA\Property(property: 'city_name', type: 'string'),
            new OA\Property(property: 'state_name', type: 'string'),
            new OA\Property(property: 'country_name', type: 'string'),

            new OA\Property(property: 'city', type: 'integer'),
            new OA\Property(property: 'state', type: 'integer'),
            new OA\Property(property: 'country', type: 'integer'),

            new OA\Property(property: 'pincode', type: 'string'),

            new OA\Property(property: 'email_id', type: 'string'),
            new OA\Property(property: 'mobile_no', type: 'string'),

            new OA\Property(property: 'aadhar_number', type: 'string'),

            new OA\Property(property: 'membership_type', type: 'string'),
            new OA\Property(property: 'membership_start_date', type: 'string'),

            new OA\Property(property: 'family', type: 'string'),

            new OA\Property(property: 'marriage_status', type: 'string'),
            new OA\Property(property: 'marriage_date', type: 'string'),

            new OA\Property(property: 'relation', type: 'string'),
            new OA\Property(property: 'notes', type: 'string'),

            new OA\Property(property: 'avatar', type: 'string'),
        ]
    ),

    OA\Response(
        response: 'UserDetailResponse',
        description: 'User Detail',
        content: new OA\JsonContent(
            ref: '#/components/schemas/UserDetailResource'
        )
    ),

    OA\Schema(
        schema: 'EditUserDetailRequest',

        required: [
            'firstname',
            'gender',
            'date_of_birth',
            'profession',
            'address',
            'city',
            'state',
            'country',
            'pincode',
            'marriage_status'
        ],

        properties: [

            new OA\Property(property: 'firstname', type: 'string'),
            new OA\Property(property: 'lastname', type: 'string'),

            new OA\Property(property: 'birth_firstname', type: 'string'),
            new OA\Property(property: 'birth_lastname', type: 'string'),

            new OA\Property(property: 'gender', type: 'string'),

            new OA\Property(
                property: 'date_of_birth',
                type: 'string',
                format: 'date'
            ),

            new OA\Property(property: 'aadhar_number', type: 'string'),

            new OA\Property(property: 'profession', type: 'string'),
            new OA\Property(property: 'sub_occupation', type: 'string'),

            new OA\Property(property: 'address', type: 'string'),

            new OA\Property(property: 'city', type: 'integer'),
            new OA\Property(property: 'state', type: 'integer'),
            new OA\Property(property: 'country', type: 'integer'),

            new OA\Property(property: 'pincode', type: 'string'),

            new OA\Property(property: 'family', type: 'string'),

            new OA\Property(property: 'marriage_status', type: 'string'),

            new OA\Property(
                property: 'marriage_start_date',
                type: 'string',
                format: 'date'
            ),

            new OA\Property(property: 'giving_no', type: 'integer'),

            new OA\Property(property: 'notes', type: 'string'),

            new OA\Property(
                property: 'avatar',
                type: 'string',
                format: 'binary'
            ),
        ]
    ),
    OA\Response(
        response: 'EditUserDetailResponse',
        description: 'User Detail Updated',
        content: new OA\JsonContent(
            example: [
                'message' => 'Profile Updated Successfully'
            ]
        )
    ),
    OA\Schema(
        schema: 'LoginRequest',
        required: ['email', 'password'],
        properties: [
            new OA\Property(property: 'email', type: 'string'),
            new OA\Property(property: 'password', type: 'string'),
        ]
    ),
    OA\Response(
        response: 'LoginResponse',
        description: 'Login',
        content: new OA\JsonContent(
            example: [
                'message' => 'Login Successfully'
            ]
        )
    ),
    OA\Schema(
        schema: 'EventResource',
        properties: [

            new OA\Property(property: 'id', type: 'integer'),
            new OA\Property(property: 'church_id', type: 'integer'),

            new OA\Property(property: 'select_type', type: 'string'),

            new OA\Property(property: 'title', type: 'string'),
            new OA\Property(property: 'description', type: 'string'),

            new OA\Property(property: 'repeats', type: 'string'),
            new OA\Property(property: 'freq', type: 'integer'),
            new OA\Property(property: 'freq_term', type: 'string'),

            new OA\Property(property: 'location', type: 'string'),
            new OA\Property(property: 'category', type: 'string'),

            new OA\Property(property: 'image', type: 'string'),

            new OA\Property(property: 'start_date', type: 'string'),
            new OA\Property(property: 'end_date', type: 'string'),

            new OA\Property(property: 'date', type: 'string'),
            new OA\Property(property: 'month', type: 'string'),

            new OA\Property(property: 'description_limit', type: 'string'),

        ]
    ),

    OA\Response(
        response: 'EventResponse',
        description: 'Event List',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/EventResource'
            )
        )
    ),
    OA\Schema(
        schema: 'EventGalleryResource',
        properties: [

            new OA\Property(
                property: 'id',
                type: 'integer'
            ),

            new OA\Property(
                property: 'path',
                type: 'string'
            ),

            new OA\Property(
                property: 'updated_at',
                type: 'string'
            ),
        ]
    ),

    OA\Response(
        response: 'EventGalleryResponse',
        description: 'Event Gallery List',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/EventGalleryResource'
            )
        )
    ),

    OA\Schema(
        schema: 'PhotosResource',
        properties: [

            new OA\Property(
                property: 'id',
                type: 'integer'
            ),
            new OA\Property(
                property: 'church_id',
                type: 'integer'
            ),

            new OA\Property(
                property: 'gallery_id',
                type: 'integer'
            ),

            new OA\Property(
                property: 'path',
                type: 'string'
            ),

            new OA\Property(
                property: 'updated_at',
                type: 'string'
            ),
        ]
    ),

    OA\Response(
        response: 'PhotosResponse',
        description: 'Event Gallery List',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/PhotosResource'
            )
        )
    ),



]
class OpenApiDefinitions {}
