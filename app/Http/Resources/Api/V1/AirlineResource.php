<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AirlineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'airline',
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'country' => $this->country,
        ];
    }
    public function withResponse($request, $response)
    {
        $response->header('Accept', 'application/json');
        $response->header('X-API-Version', '1.0.0');
        $response->header('X-Resource-Type', 'Airplane');
        $response->header('X-Response-Time', microtime(true) - LARAVEL_START);
    }
}
