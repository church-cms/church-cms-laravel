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

]
class OpenApiDefinitions {}
