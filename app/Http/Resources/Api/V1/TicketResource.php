<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        //Only try to get passenger if ticket exists
        $passenger = $this->whenLoaded('passenger');
        
        // ✅ Check if passenger is loaded AND not missing
        $hasPassenger = $passenger && !($passenger instanceof \Illuminate\Http\Resources\MissingValue);

        return [
            'type' => 'ticket',
            'id' => $this->id,
            'ticket_number' => $this->ticket_number,
            'seat_number' => $this->seat_number,
            'class' => $this->class,
            'meal_preference' => $this->meal_preference,
            'issued_at' => $this->issued_at?->format('Y-m-d H:i:s'),
            'notes' => $this->notes,
            'status' => $this->status,

            //Only add passenger data if it exists
            'first_name' => $hasPassenger ? $passenger->first_name : null,
            'last_name' => $hasPassenger ? $passenger->last_name : null,
            'full_name' => $hasPassenger ? $passenger->first_name . ' ' . $passenger->last_name : null,
            'email' => $hasPassenger ? $passenger->email : null,
            'phone' => $hasPassenger ? $passenger->phone : null,

            //Only create PassengerResource if passenger exists
            'passenger' => $hasPassenger ? new PassengerResource($passenger) : null,

            'links' => [
                'self' => route('api.v1.tickets.show', $this->id),
                'collection' => route('api.v1.tickets.index'),
                'passenger' => $this->passenger_id ? route('api.v1.passengers.show', $this->passenger_id) : null,
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