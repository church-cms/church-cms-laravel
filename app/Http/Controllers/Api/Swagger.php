<?php

namespace App\Http\Controllers\Api;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "My API",
    description: "API description",
    contact: new OA\Contact(
        email: "contact@example.com"
    ),
    license: new OA\License(
        name: "Apache 2.0",
        url: "https://www.apache.org/licenses/LICENSE-2.0.html"
    )
)]
class Swagger
{
    // empty class is OK
}