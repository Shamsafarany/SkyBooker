<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AirportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'airport',
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'city' => $this->city,
            'country' => $this->country,
            'terminals' => $this->terminals,
            'status' => $this->status,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'full_name' => $this->name . ' (' . $this->code . ')',
            'location' => $this->city . ', ' . $this->country,
            'flights_count' => $this->whenCounted('flights'),
            'originating_flights_count' => $this->whenCounted('originFlights'),
            'destination_flights_count' => $this->whenCounted('destinationFlights'),
            'flights' => FlightResource::collection($this->whenLoaded('flights')),
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
