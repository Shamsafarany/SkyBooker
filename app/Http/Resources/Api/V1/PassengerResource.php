<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PassengerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'passenger',
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => trim($this->first_name . ' ' . $this->last_name),
            'email' => $this->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->date_of_birth,
            'nationality' => $this->nationality,
            'passport_number' => $this->passport_number,
            'id_number' => $this->id_number,
            'seat_number' => $this->seat_number,
            'meal_preference' => $this->meal_preference,
            'status' => $this->status,
            
            // Use closure to prevent MissingValue
            'booking' => $this->whenLoaded('booking', function () {
                return new BookingResource($this->booking);
            }),
            
            // Check if ticket exists before creating resource
            'ticket' => $this->whenLoaded('ticket', function () {
                // Only create TicketResource if ticket is not null
                if ($this->ticket && !($this->ticket instanceof \Illuminate\Http\Resources\MissingValue)) {
                    return new TicketResource($this->ticket);
                }
                return null;
            }),
            
            'links' => [
                'self' => route('api.v1.passengers.show', $this->id),
                'collection' => route('api.v1.passengers.index'),
                'booking' => $this->booking_id ? route('api.v1.bookings.show', $this->booking_id) : null,
            ],
            
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
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