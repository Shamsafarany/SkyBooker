<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlightResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'flight',
            'id' => $this->id,
            'flight_number' => $this->flight_number,
            'status' => $this->status,
            'price' => number_format($this->price, 2),
            'departure' => [
                'date' => $this->departure_date,
                'time' => $this->departure_time,
                'datetime' => $this->departure_date . ' ' . $this->departure_time,
            ],
            'arrival' => [
                'date' => $this->arrival_date,
                'time' => $this->arrival_time,
                'datetime' => $this->arrival_date . ' ' . $this->arrival_time,
            ],
            'duration' => $this->duration,
            'seats' => [
                'total' => $this->total_seats,
                'available' => $this->available_seats,
                'booked' => $this->booked_seats,
            ],
            'airline' => new AirlineResource($this->whenLoaded('airline')),
            'origin' => new AirportResource($this->whenLoaded('origin')),
            'destination' => new AirportResource($this->whenLoaded('destination')),
            'airplane' => new AirplaneResource($this->whenLoaded('airplane')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'links' => [
                // Self
                'self' => route('api.v1.flights.show', $this->id),
                
                // Collection
                'collection' => route('api.v1.flights.index'),
                
                // Related resources
                'airline' => route('api.v1.airlines.show', $this->airline_id),
                'origin_airport' => route('api.v1.airports.show', $this->origin_airport_id),
                'destination_airport' => route('api.v1.airports.show', $this->destination_airport_id),
                'airplane' => route('api.v1.airplanes.show', $this->airplane_id),
            ],
        ];
    }
}
