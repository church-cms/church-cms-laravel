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

    OA\Schema(
        schema: 'SermonResource',
        properties: [
            new OA\Property(property: 'sermon_id',     type: 'integer'),
            new OA\Property(property: 'author',        type: 'string'),
            new OA\Property(property: 'title',         type: 'string'),
            new OA\Property(property: 'description',   type: 'string'),
            new OA\Property(property: 'cover_image',   type: 'string'),
            new OA\Property(property: 'total_likes',   type: 'integer'),
            new OA\Property(property: 'total_unlikes', type: 'integer'),
            new OA\Property(
                property: 'like',
                type: 'integer',
                description: '1 = liked, 0 = not liked, 2 = not yet voted'
            ),
            new OA\Property(
                property: 'unlike',
                type: 'integer',
                description: '1 = unliked, 0 = not unliked, 2 = not yet voted'
            ),
            new OA\Property(property: 'audio_count',   type: 'integer'),
            new OA\Property(property: 'video_count',   type: 'integer'),
            new OA\Property(property: 'file_count',    type: 'integer'),
        ]
    ),

    OA\Response(
        response: 'SermonResponse',
        description: 'Sermon List',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/SermonResource'
            )
        )
    ),

    OA\Schema(
        schema: 'SermonLikeRequest',
        required: ['entity_id'],
        properties: [
            new OA\Property(
                property: 'entity_id',
                type: 'integer',
                description: 'The ID of the sermon to like'
            ),
        ]
    ),

    OA\Response(
        response: 'SermonLikeResponse',
        description: 'Sermon Like / Unlike Result',
        content: new OA\JsonContent(
            example: [
                'message' => 'You have liked this sermon'
            ]
        )
    ),
    OA\Schema(
        schema: 'SermonUnLikeRequest',
        required: ['entity_id'],
        properties: [
            new OA\Property(
                property: 'entity_id',
                type: 'integer',
                description: 'The ID of the sermon to unlike'
            ),
        ]
    ),

    OA\Response(
        response: 'SermonUnLikeResponse',
        description: 'Sermon Like / Unlike Result',
        content: new OA\JsonContent(
            example: [
                'message' => 'You have unliked this sermon'
            ]
        )
    ),

    OA\Schema(
        schema: 'FavoritesRequest',
        required: ['entity_id'],
        properties: [
            new OA\Property(
                property: 'entity_id',
                type: 'integer',
                description: 'The ID of the sermon to Favorites'
            ),
        ]
    ),
    OA\Response(
        response: 'FavoritesResponse',
        description: 'Sermon Favorites',
        content: new OA\JsonContent(
            example: [
                'message' => 'You have favorites this sermon'
            ]
        )
    ),

    OA\Schema(
        schema: 'SermonsResource',
        properties: [
            new OA\Property(property: 'sermon_id',     type: 'integer'),
            new OA\Property(property: 'author',        type: 'string'),
            new OA\Property(property: 'title',         type: 'string'),
            new OA\Property(property: 'description',   type: 'string'),
            new OA\Property(property: 'cover_image',   type: 'string'),
            new OA\Property(property: 'total_likes',   type: 'integer'),
            new OA\Property(property: 'total_unlikes', type: 'integer'),
            new OA\Property(
                property: 'like',
                type: 'integer',
                description: '1 = liked, 0 = not liked, 2 = not yet voted'
            ),
            new OA\Property(
                property: 'unlike',
                type: 'integer',
                description: '1 = unliked, 0 = not unliked, 2 = not yet voted'
            ),
            new OA\Property(property: 'audio_count',   type: 'integer'),
            new OA\Property(property: 'video_count',   type: 'integer'),
            new OA\Property(property: 'file_count',    type: 'integer'),
        ]
    ),

    OA\Response(
        response: 'SermonsResponse',
        description: 'Sermon List',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/SermonsResource'
            )
        )
    ),
    OA\Schema(
        schema: 'SermonlinkResource',
        properties: [

            new OA\Property(
                property: 'sermons_id',
                type: 'integer'
            ),

            new OA\Property(
                property: 'title',
                type: 'string'
            ),

            new OA\Property(
                property: 'total_likes',
                type: 'integer'
            ),

            new OA\Property(
                property: 'total_unlikes',
                type: 'integer'
            ),

            new OA\Property(
                property: 'like',
                type: 'integer'
            ),

            new OA\Property(
                property: 'unlike',
                type: 'integer'
            ),

            new OA\Property(
                property: 'type',
                type: 'string'
            ),

            new OA\Property(
                property: 'location',
                type: 'string'
            ),

            new OA\Property(
                property: 'url',
                type: 'string'
            ),
        ]
    ),

    OA\Response(
        response: 'SermonlinkResponse',
        description: 'Sermon Media List',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/SermonlinkResource'
            )
        )
    ),
    OA\Schema(
    schema: 'MediaFileResource',
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
            property: 'name',
            type: 'string'
        ),

        new OA\Property(
            property: 'description',
            type: 'string'
        ),

        new OA\Property(
            property: 'media_type',
            type: 'string'
        ),

        new OA\Property(
            property: 'url',
            type: 'string'
        ),
    ]
),

OA\Response(
    response: 'MediaFileResponse',
    description: 'Media List',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: '#/components/schemas/MediaFileResource'
        )
    )
),

    OA\Schema(
        schema: 'BulletinResource',
        properties: [
            new OA\Property(property: 'id',          type: 'integer'),
            new OA\Property(property: 'church_id',   type: 'integer'),
            new OA\Property(property: 'name',        type: 'string'),
            new OA\Property(property: 'type',        type: 'string',  description: 'Bulletin type (e.g. weekly, monthly)'),
            new OA\Property(property: 'week',        type: 'integer', description: 'Week number for weekly bulletins'),
            new OA\Property(property: 'month',       type: 'integer', description: 'Month number for monthly bulletins'),
            new OA\Property(property: 'year',        type: 'integer'),
            new OA\Property(property: 'cover_image', type: 'string'),
            new OA\Property(property: 'path',        type: 'string',  description: 'URL to the bulletin document'),
        ]
    ),

    OA\Response(
        response: 'BulletinResponse',
        description: 'Bulletin List',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/BulletinResource'
            )
        )
    ),

    OA\Schema(
        schema: 'FundResource',
        properties: [
            new OA\Property(property: 'id',         type: 'integer'),
            new OA\Property(property: 'church_id',  type: 'integer'),
            new OA\Property(property: 'name',       type: 'string',  description: 'Donor full name'),
            new OA\Property(property: 'method',     type: 'string',  description: 'Payment gateway display name'),
            new OA\Property(property: 'amount',     type: 'number',  format: 'float'),
            new OA\Property(property: 'status',     type: 'string',  description: 'Fund status (e.g. Request, Deposited)'),
            new OA\Property(property: 'created_at', type: 'string',  description: 'Date formatted as d-m-Y h:i A'),
        ]
    ),

    OA\Response(
        response: 'FundResponse',
        description: 'Fund List',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/FundResource'
            )
        )
    ),

    OA\Schema(
        schema: 'AddFundRequest',
        required: ['amount', 'payaccount_id'],
        properties: [
            new OA\Property(property: 'amount',        type: 'number', format: 'float', description: 'Donation amount'),
            new OA\Property(property: 'payaccount_id', type: 'integer', description: 'Payment account ID to use'),
        ]
    ),

    OA\Response(
        response: 'AddFundResponse',
        description: 'Fund Request Submitted',
        content: new OA\JsonContent(
            example: [
                'status'  => true,
                'message' => 'Fund Requested Successfully'
            ]
        )
    ),

    OA\Schema(
        schema: 'PaymentgatewayResource',
        properties: [
            new OA\Property(property: 'id',           type: 'integer'),
            new OA\Property(property: 'name',         type: 'string', description: 'Internal gateway name'),
            new OA\Property(property: 'display_name', type: 'string', description: 'Human-readable gateway label'),
            new OA\Property(property: 'instructions', type: 'string', description: 'Payment instructions for the user'),
            new OA\Property(property: 'status',       type: 'integer', description: '1 = church has an active account for this gateway, 0 = not configured'),
        ]
    ),

    OA\Response(
        response: 'PaymentgatewayResponse',
        description: 'Payment Gateway List',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/PaymentgatewayResource'
            )
        )
    ),

    OA\Schema(
        schema: 'PayaccountResource',
        properties: [
            new OA\Property(property: 'id',                 type: 'integer'),
            new OA\Property(property: 'paymentgateway_id',  type: 'integer'),
            new OA\Property(property: 'gatewayname',        type: 'string'),
            new OA\Property(property: 'display_name',       type: 'string'),
            new OA\Property(property: 'status',             type: 'integer'),
            new OA\Property(property: 'comments',           type: 'string'),
            new OA\Property(property: 'param1',             type: 'string'),
            new OA\Property(property: 'param2',             type: 'string'),
            new OA\Property(property: 'param3',             type: 'string'),
            new OA\Property(property: 'param4',             type: 'string'),
            new OA\Property(property: 'param5',             type: 'string'),
            new OA\Property(property: 'param6',             type: 'string'),
            new OA\Property(property: 'param7',             type: 'string'),
            new OA\Property(property: 'param8',             type: 'string'),
        ]
    ),

    OA\Response(
        response: 'PayaccountDetailResponse',
        description: 'Payment Account Details',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(
                    property: 'data',
                    ref: '#/components/schemas/PayaccountResource'
                ),
            ]
        )
    ),

    // ── Quotes ──────────────────────────────────────────────────────────────

    OA\Schema(
        schema: 'QuoteResource',
        properties: [
            new OA\Property(property: 'id',              type: 'integer'),
            new OA\Property(property: 'image',           type: 'string',  nullable: true),
            new OA\Property(property: 'text',            type: 'string',  nullable: true),
            new OA\Property(property: 'tamil_quotes',    type: 'string',  nullable: true),
            new OA\Property(property: 'english_quotes',  type: 'string',  nullable: true),
            new OA\Property(property: 'publish_on',      type: 'string',  nullable: true, description: 'Formatted as d-m-Y H:i:s'),
        ]
    ),

    OA\Response(
        response: 'QuoteResponse',
        description: "Today's Quote",
        content: new OA\JsonContent(
            ref: '#/components/schemas/QuoteResource'
        )
    ),

    // ── Prayer Requests ──────────────────────────────────────────────────────

    OA\Schema(
        schema: 'PrayerRequestResource',
        properties: [
            new OA\Property(property: 'id',                      type: 'integer'),
            new OA\Property(property: 'requested_person',        type: 'string'),
            new OA\Property(property: 'requested_person_avatar', type: 'string',  nullable: true),
            new OA\Property(property: 'category',                type: 'string',  nullable: true),
            new OA\Property(property: 'text',                    type: 'string'),
            new OA\Property(property: 'status',                  type: 'string'),
            new OA\Property(property: 'status_label',            type: 'string'),
            new OA\Property(property: 'response_status',         type: 'integer', description: '1 = current user has prayed, 0 = not'),
            new OA\Property(property: 'member_count',            type: 'integer'),
            new OA\Property(property: 'guest_count',             type: 'integer'),
            new OA\Property(property: 'anonymous_count',         type: 'integer'),
            new OA\Property(property: 'total_prayers',           type: 'integer'),
            new OA\Property(property: 'days_remaining',          type: 'integer', nullable: true),
            new OA\Property(property: 'expires_at',              type: 'string',  nullable: true, format: 'date-time'),
            new OA\Property(property: 'is_pinned',               type: 'boolean'),
            new OA\Property(property: 'date',                    type: 'string',  description: 'Formatted as d-m-Y h:i A'),
        ]
    ),

    OA\Response(
        response: 'PrayerRequestResponse',
        description: 'Public Prayer Request Board',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/PrayerRequestResource')
        )
    ),

    OA\Schema(
        schema: 'PrayerRequestUserResource',
        properties: [
            new OA\Property(property: 'id',             type: 'integer'),
            new OA\Property(property: 'avatar',         type: 'string',  nullable: true),
            new OA\Property(property: 'category',       type: 'string',  nullable: true),
            new OA\Property(property: 'text',           type: 'string'),
            new OA\Property(property: 'status',         type: 'string'),
            new OA\Property(property: 'display_status', type: 'string'),
            new OA\Property(property: 'total_prayers',  type: 'integer'),
            new OA\Property(property: 'date',           type: 'string',  description: 'Formatted as d-m-Y h:i A'),
        ]
    ),

    OA\Response(
        response: 'PrayerRequestUserResponse',
        description: "Current user's prayer requests",
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/PrayerRequestUserResource')
        )
    ),

    OA\Schema(
        schema: 'PrayerCategoryResource',
        properties: [
            new OA\Property(property: 'id',          type: 'integer'),
            new OA\Property(property: 'name',        type: 'string'),
            new OA\Property(property: 'description', type: 'string'),
            new OA\Property(property: 'church_id',   type: 'integer'),
            new OA\Property(property: 'date',        type: 'string', description: 'Formatted as d-m-Y h:i A'),
        ]
    ),

    OA\Response(
        response: 'PrayerCategoryResponse',
        description: 'Prayer Category List',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/PrayerCategoryResource')
        )
    ),

    OA\Schema(
        schema: 'SubmitPrayerRequest',
        required: ['category_id', 'text'],
        properties: [
            new OA\Property(property: 'category_id', type: 'integer', description: 'ID of the prayer category'),
            new OA\Property(property: 'text',        type: 'string',  minLength: 10, maxLength: 500, description: 'Prayer request text'),
        ]
    ),

    OA\Response(
        response: 'PrayerRequestCreateResponse',
        description: 'Prayer Request Submitted',
        content: new OA\JsonContent(
            example: ['message' => 'Prayer request submitted successfully']
        )
    ),

    // ── Prayer Participants ───────────────────────────────────────────────────

    OA\Response(
        response: 'PrayerParticipantResponse',
        description: 'Prayer Participation Recorded',
        content: new OA\JsonContent(
            example: ['message' => 'Thank you for praying!']
        )
    ),

    // ── Helps ─────────────────────────────────────────────────────────────────

    OA\Schema(
        schema: 'HelpResource',
        properties: [
            new OA\Property(property: 'id',               type: 'integer'),
            new OA\Property(property: 'requested_person', type: 'string'),
            new OA\Property(property: 'title',            type: 'string'),
            new OA\Property(property: 'description',      type: 'string'),
            new OA\Property(property: 'contact_details',  type: 'string'),
            new OA\Property(property: 'status',           type: 'string'),
            new OA\Property(property: 'display_status',   type: 'string'),
            new OA\Property(property: 'expires_at',       type: 'string', nullable: true, description: 'Formatted as d-m-Y h:i A'),
        ]
    ),

    OA\Response(
        response: 'HelpResponse',
        description: 'Help Request List',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/HelpResource')
        )
    ),

    OA\Schema(
        schema: 'HelpUserResource',
        properties: [
            new OA\Property(property: 'id',              type: 'integer'),
            new OA\Property(property: 'title',           type: 'string'),
            new OA\Property(property: 'description',     type: 'string'),
            new OA\Property(property: 'contact_details', type: 'string'),
            new OA\Property(property: 'status',          type: 'string'),
            new OA\Property(property: 'display_status',  type: 'string'),
            new OA\Property(property: 'expires_at',      type: 'string', nullable: true, description: 'Formatted as d-m-Y h:i A'),
        ]
    ),

    OA\Response(
        response: 'HelpUserResponse',
        description: "Current user's help requests",
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/HelpUserResource')
        )
    ),

    OA\Schema(
        schema: 'HelpAddRequest',
        required: ['title', 'description', 'contact_details'],
        properties: [
            new OA\Property(property: 'title',           type: 'string',  maxLength: 20),
            new OA\Property(property: 'description',     type: 'string',  maxLength: 100),
            new OA\Property(property: 'contact_details', type: 'string',  description: '10-digit contact number'),
        ]
    ),

    OA\Response(
        response: 'HelpCreateResponse',
        description: 'Help Request Submitted',
        content: new OA\JsonContent(
            example: ['message' => 'Help Request Added Successfully']
        )
    ),

    OA\Response(
        response: 'HelpCloseResponse',
        description: 'Help Request Closed',
        content: new OA\JsonContent(
            example: ['message' => 'Help Request Closed Successfully']
        )
    ),

    // ── Groups ────────────────────────────────────────────────────────────────

    OA\Schema(
        schema: 'GroupLinkResource',
        properties: [
            new OA\Property(property: 'group_id',          type: 'integer'),
            new OA\Property(property: 'group_name',        type: 'string'),
            new OA\Property(property: 'cover_image',       type: 'string'),
            new OA\Property(property: 'started',           type: 'string',  description: 'Formatted as M-Y'),
            new OA\Property(property: 'group_category',    type: 'string'),
            new OA\Property(property: 'group_type',        type: 'string'),
            new OA\Property(property: 'group_description', type: 'string'),
            new OA\Property(
                property: 'user_permissions',
                type: 'array',
                items: new OA\Items(type: 'string')
            ),
            new OA\Property(
                property: 'group_members',
                type: 'array',
                items: new OA\Items(type: 'string')
            ),
        ]
    ),

    OA\Response(
        response: 'GroupLinkResponse',
        description: 'Group Membership List',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/GroupLinkResource')
        )
    ),

    // ── Messages ──────────────────────────────────────────────────────────────

    OA\Schema(
        schema: 'SendMailResource',
        properties: [
            new OA\Property(property: 'id',         type: 'integer'),
            new OA\Property(property: 'subject',    type: 'string'),
            new OA\Property(property: 'message',    type: 'string'),
            new OA\Property(property: 'attachment', type: 'string'),
            new OA\Property(property: 'sentAt',     type: 'string', description: 'Human-readable time difference'),
            new OA\Property(property: 'readStatus', type: 'integer', description: '1 = read, 0 = unread'),
            new OA\Property(property: 'readAt',     type: 'string'),
        ]
    ),

    OA\Response(
        response: 'MessageListResponse',
        description: 'Message List',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/SendMailResource')
                ),
            ]
        )
    ),

    OA\Response(
        response: 'MessageReadResponse',
        description: 'Message Marked as Read',
        content: new OA\JsonContent(
            example: ['success' => true]
        )
    ),

    // ── Notifications ─────────────────────────────────────────────────────────

    OA\Schema(
        schema: 'NotificationResource',
        properties: [
            new OA\Property(property: 'id',               type: 'string',  description: 'UUID'),
            new OA\Property(property: 'type',             type: 'string'),
            new OA\Property(property: 'notifiable_type',  type: 'string'),
            new OA\Property(property: 'notifiable_id',    type: 'integer'),
            new OA\Property(property: 'data_message',     type: 'object'),
            new OA\Property(property: 'web_message',      type: 'object'),
            new OA\Property(property: 'read_at',          type: 'string',  nullable: true),
            new OA\Property(property: 'created_at',       type: 'string',  description: 'Human-readable time difference'),
        ]
    ),

    OA\Response(
        response: 'NotificationListResponse',
        description: 'Notification List',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'type',    type: 'string'),
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/NotificationResource')
                ),
            ]
        )
    ),

    // ── Contact ───────────────────────────────────────────────────────────────

    OA\Schema(
        schema: 'ContactRequest',
        required: ['fullname', 'email', 'mobile', 'query_message'],
        properties: [
            new OA\Property(property: 'fullname',       type: 'string',  maxLength: 25),
            new OA\Property(property: 'email',          type: 'string',  format: 'email'),
            new OA\Property(property: 'mobile',         type: 'string',  description: '10-digit mobile number'),
            new OA\Property(property: 'query_message',  type: 'string'),
        ]
    ),

    OA\Response(
        response: 'ContactResponse',
        description: 'Contact Form Submitted',
        content: new OA\JsonContent(
            example: ['status' => true, 'message' => 'Contact Submitted Successfully']
        )
    ),

    // ── Feedback ──────────────────────────────────────────────────────────────

    OA\Schema(
        schema: 'FeedbackMessageResource',
        properties: [
            new OA\Property(property: 'type',    type: 'string', description: '"send" or "receive"'),
            new OA\Property(property: 'time',    type: 'string', description: 'd-m-Y H:i:s'),
            new OA\Property(property: 'message', type: 'string'),
        ]
    ),

    OA\Schema(
        schema: 'FeedbackResource',
        properties: [
            new OA\Property(property: 'feedback_id',   type: 'integer'),
            new OA\Property(
                property: 'messages',
                type: 'array',
                items: new OA\Items(ref: '#/components/schemas/FeedbackMessageResource')
            ),
            new OA\Property(property: 'category',      type: 'string'),
            new OA\Property(property: 'status',        type: 'string', description: '"Message Not Yet Viewed" | "Message Has Been Seen" | "Action Has Been Taken"'),
            new OA\Property(property: 'created_on',    type: 'string', description: 'd-m-Y H:i:s'),
            new OA\Property(property: 'last_reply_by', type: 'string'),
            new OA\Property(property: 'last_reply_on', type: 'string', description: 'd-m-Y H:i:s'),
        ]
    ),

    OA\Response(
        response: 'FeedbackResponse',
        description: 'Feedback List',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/FeedbackResource')
        )
    ),

    OA\Response(
        response: 'FeedbackCategoryResponse',
        description: 'Feedback Category List',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(type: 'string')
        )
    ),

    OA\Schema(
        schema: 'AddFeedbackRequest',
        required: ['message', 'category'],
        properties: [
            new OA\Property(property: 'message',  type: 'string', maxLength: 300),
            new OA\Property(property: 'category', type: 'string'),
        ]
    ),

    OA\Response(
        response: 'AddFeedbackResponse',
        description: 'Feedback Submitted',
        content: new OA\JsonContent(
            example: ['message' => 'Message Sent Successfully']
        )
    ),

    // ── Church Detail ─────────────────────────────────────────────────────────

    OA\Schema(
        schema: 'ChurchDetailResource',
        properties: [
            new OA\Property(property: 'church_name',   type: 'string'),
            new OA\Property(property: 'church_logo',   type: 'string'),
            new OA\Property(property: 'short_summary', type: 'string'),
            new OA\Property(property: 'long_summary',  type: 'string'),
            new OA\Property(property: 'quotes',        type: 'string'),
            new OA\Property(property: 'phone',         type: 'string'),
            new OA\Property(property: 'email',         type: 'string'),
            new OA\Property(property: 'address',       type: 'string'),
            new OA\Property(property: 'latitude',      type: 'string'),
            new OA\Property(property: 'longitude',     type: 'string'),
            new OA\Property(property: 'website',       type: 'string'),
            new OA\Property(property: 'facebook',      type: 'string'),
            new OA\Property(property: 'twitter',       type: 'string'),
            new OA\Property(property: 'instagram',     type: 'string'),
        ]
    ),

    OA\Response(
        response: 'ChurchDetailResponse',
        description: 'Church Detail',
        content: new OA\JsonContent(
            ref: '#/components/schemas/ChurchDetailResource'
        )
    ),

    // ── Update Token ──────────────────────────────────────────────────────────

    OA\Schema(
        schema: 'UpdateTokenRequest',
        required: ['platform_token'],
        properties: [
            new OA\Property(property: 'platform_token', type: 'string', description: 'Push notification device token'),
        ]
    ),

    OA\Response(
        response: 'UpdateTokenResponse',
        description: 'Token Updated',
        content: new OA\JsonContent(
            example: ['message' => 'Token Updated Successfully']
        )
    ),

    // ── Profession & Marriage Status ──────────────────────────────────────────

    OA\Response(
        response: 'ProfessionResponse',
        description: 'Profession List',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(type: 'string'),
                    example: ['business', 'doctor', 'engineer', 'government_employee', 'home_maker', 'lawyer', 'pastor', 'police', 'professionals', 'self_employed', 'student', 'teacher', 'others']
                ),
            ]
        )
    ),

    OA\Response(
        response: 'MarriageStatusResponse',
        description: 'Marriage Status List',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(type: 'string')
                ),
            ]
        )
    ),

    // ── Reset Change Password ─────────────────────────────────────────────────

    OA\Schema(
        schema: 'ResetChangePasswordRequest',
        required: ['mobile_no', 'oldpassword', 'newpassword', 'confirmpassword'],
        properties: [
            new OA\Property(property: 'mobile_no',       type: 'string', description: 'Registered mobile number'),
            new OA\Property(property: 'oldpassword',     type: 'string', description: 'OTP token from SMS'),
            new OA\Property(property: 'newpassword',     type: 'string', minLength: 8),
            new OA\Property(property: 'confirmpassword', type: 'string', description: 'Must match newpassword'),
        ]
    ),

    OA\Response(
        response: 'ResetChangePasswordResponse',
        description: 'Password Change Result',
        content: new OA\JsonContent(
            example: ['message' => 'Changed Password Successfully']
        )
    ),

    // ── Attendance ────────────────────────────────────────────────────────────

    OA\Schema(
        schema: 'AttendanceEventItem',
        properties: [
            new OA\Property(property: 'id',               type: 'integer'),
            new OA\Property(property: 'title',            type: 'string'),
            new OA\Property(property: 'category',         type: 'string'),
            new OA\Property(property: 'start_date',       type: 'string'),
            new OA\Property(property: 'end_date',         type: 'string'),
            new OA\Property(property: 'today_session_id', type: 'integer', nullable: true),
            new OA\Property(property: 'is_locked',        type: 'boolean'),
        ]
    ),

    OA\Response(
        response: 'AttendanceEventsResponse',
        description: 'Attendance-enabled events assigned to the staff member',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/AttendanceEventItem')
                ),
            ]
        )
    ),

    OA\Schema(
        schema: 'OpenSessionRequest',
        required: ['event_id'],
        properties: [
            new OA\Property(property: 'event_id',        type: 'integer'),
            new OA\Property(property: 'attendance_date', type: 'string', format: 'date', description: 'Defaults to today'),
        ]
    ),

    OA\Response(
        response: 'OpenSessionResponse',
        description: 'Attendance session opened or retrieved',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'session_id',      type: 'integer'),
                new OA\Property(property: 'event_id',        type: 'integer'),
                new OA\Property(property: 'event_title',     type: 'string'),
                new OA\Property(property: 'attendance_date', type: 'string'),
                new OA\Property(property: 'is_locked',       type: 'boolean'),
            ]
        )
    ),

    OA\Schema(
        schema: 'AttendanceScanRequest',
        required: ['session_id', 'member_username'],
        properties: [
            new OA\Property(property: 'session_id',      type: 'integer'),
            new OA\Property(property: 'member_username', type: 'string'),
        ]
    ),

    OA\Response(
        response: 'AttendanceScanResponse',
        description: 'Check-in result (200 checked_in / 409 already_checked_in)',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status',      type: 'string', description: '"checked_in" or "already_checked_in"'),
                new OA\Property(property: 'member_name', type: 'string'),
                new OA\Property(property: 'avatar_url',  type: 'string', nullable: true),
                new OA\Property(property: 'scanned_at',  type: 'string'),
            ]
        )
    ),

    OA\Response(
        response: 'SessionLockResponse',
        description: 'Session locked',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message',   type: 'string'),
                new OA\Property(property: 'locked_at', type: 'string'),
            ]
        )
    ),

    OA\Schema(
        schema: 'AttendeeItem',
        properties: [
            new OA\Property(property: 'member_id',   type: 'integer'),
            new OA\Property(property: 'member_name', type: 'string'),
            new OA\Property(property: 'avatar_url',  type: 'string', nullable: true),
            new OA\Property(property: 'mobile_no',   type: 'string'),
            new OA\Property(property: 'scanned_at',  type: 'string'),
            new OA\Property(property: 'scanned_by',  type: 'string', nullable: true),
        ]
    ),

    OA\Response(
        response: 'SessionReportResponse',
        description: 'Session attendee report',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'session_id',      type: 'integer'),
                new OA\Property(property: 'event_title',     type: 'string'),
                new OA\Property(property: 'attendance_date', type: 'string'),
                new OA\Property(property: 'is_locked',       type: 'boolean'),
                new OA\Property(property: 'total',           type: 'integer'),
                new OA\Property(
                    property: 'attendees',
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/AttendeeItem')
                ),
            ]
        )
    ),

]
class OpenApiDefinitions {}
