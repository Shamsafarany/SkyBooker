<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PassengerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type'=> 'passenger',
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->date_of_birth,
            'nationality' => $this->nationality,
            'passport_number' => $this->passport_number,
            'id_number' => $this->id_number,
            'seat_number' => $this->seat_number,
            'meal_preference' => $this->meal_preference,
            'status' => $this->status,
            
            'booking' => new BookingResource($this->whenLoaded('booking')),
            'ticket' => new TicketResource($this->whenLoaded('ticket')),
            
            'links' => [
                'self' => route('api.v1.passengers.show', $this->id),
                'collection' => route('api.v1.passengers.index'),
                'booking' => route('api.v1.bookings.show', $this->booking_id),
            ],
            
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
    public function withResponse($request, $response)
    {
        $response->header('Accept', 'application/json');
        $response->header('X-API-Version', '1.0.0');
        $response->header('X-Resource-Type', 'Passenger');
        $response->header('X-Response-Time', microtime(true) - LARAVEL_START);
    }
}
