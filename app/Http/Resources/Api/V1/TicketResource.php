<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $passenger = $this->whenLoaded('passenger');

        return [
            'type' => 'ticket',
            'id' => $this->id,
            'ticket_number' => $this->ticket_number,
            'seat_number' => $this->seat_number,
            'class' => $this->class,
            'meal_preference' => $this->meal_preference,
            'issued_at' => $this->issued_at?->format('Y-m-d H:i:s'),
            'notes' => $this->notes,

            // ✅ Get from passenger relationship (NOT from ticket)
            'first_name' => $passenger?->first_name,
            'last_name' => $passenger?->last_name,
            'full_name' => $passenger ? $passenger->first_name . ' ' . $passenger->last_name : null,
            'email' => $passenger?->email,
            'phone' => $passenger?->phone,
            'status' => $this->status,  // ✅ This IS in tickets table

            // ✅ Nested passenger resource
            'passenger' => new PassengerResource($passenger),

            'links' => [
                'self' => route('api.v1.tickets.show', $this->id),
                'collection' => route('api.v1.tickets.index'),
                'passenger' => route('api.v1.passengers.show', $this->passenger_id),
            ],

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
    public function withResponse($request, $response)
    {
        $response->header('Accept', 'application/json');
        $response->header('X-API-Version', '1.0.0');
        $response->header('X-Resource-Type', 'Ticket');
        $response->header('X-Response-Time', microtime(true) - LARAVEL_START);
    }
}
