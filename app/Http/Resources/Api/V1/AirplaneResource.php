<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AirplaneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'airplane',
            'id' => $this->id,
            'model' => $this->model,
            'manufacturer' => $this->manufacturer,
            'registration' => $this->registration,
            'capacity' => $this->capacity,
            'year' => $this->year,
            'status' => $this->status,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
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
