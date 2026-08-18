<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'booking',
            'id' => $this->id,
            'booking_reference' => $this->booking_reference,
            'number_of_seats' => $this->number_of_seats,
            'total_price' => number_format($this->total_price, 2),
            'status' => $this->status,
            'booking_date' => $this->booking_date?->format('Y-m-d H:i:s'),
            'notes' => $this->notes,
            'special_requests' => $this->special_requests,
            
            // ========================================
            //'user' => UserResource::make($this->whenLoaded('user')),
            'flight' => new FlightResource($this->whenLoaded('flight')),
            //'passengers' => PassengerResource::collection($this->whenLoaded('passengers')),
            //'tickets' => TicketResource::collection($this->whenLoaded('tickets')),
            
            'passenger_count' => $this->whenLoaded('passengers', function() {
                return $this->passengers->count();
            }),
            
            'ticket_count' => $this->whenLoaded('tickets', function() {
                return $this->tickets->count();
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
