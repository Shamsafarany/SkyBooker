<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'SkyBooker API',
    version: '1.0.0',
    description: 'Flight Booking System API'
)]
#[OA\Server(
    url: 'http://localhost:8000',
    description: 'Local Server'
)]
class OpenApiSpec
{
}