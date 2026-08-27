<?php

namespace App\Services\Admin;

use App\Models\Ticket;
use App\Models\Passenger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use \App\Http\Resources\Api\V1\TicketResource;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketService
{
    public function getApiList()
    {
        Log::info("TICKET INDEX: Fetching tickets");

        $tickets = Ticket::with(['passenger'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return [
            'success' => true,
            'tickets' => $tickets
        ];
    }

    public function getApiShow(Ticket $ticket)
    {
        if (!$ticket) {
            return [
                'success' => false,
                'message' => "Ticket with ID {$ticket->id} not found."
            ];
        }
        $key = "api.tickets.show.{$ticket->id}";

        $data = Cache::remember($key, 60, function () use ($ticket) {
            Log::info("TICKET SHOW: Cache MISS for ID {$ticket->id}");

            $ticket->load(['passenger']);

            return (new TicketResource($ticket))->resolve();
        });

        return [
            'success' => true,
            'ticket' => $data
        ];
    }

    public function getAdminShow(string $id)
    {
        $ticket = Ticket::with([
            'passenger',
            'passenger.booking',
            'passenger.booking.flight',
            'passenger.booking.flight.airline',
            'passenger.booking.flight.origin',
            'passenger.booking.flight.destination',
        ])->findOrFail($id);

        Log::channel('booking')->info('Ticket loaded for admin show', [
            'ticket_id' => $ticket->id,
        ]);

        return [
            'success' => true,
            'ticket' => $ticket
        ];
    }

    public function create(array $data)
    {
        $passenger = Passenger::find($data['passenger_id']);
        if (!$passenger || !$passenger->booking) {
            return [
                'success' => false,
                'message' => 'Passenger does not have a booking.'
            ];
        }
        $flight = $passenger->booking->flight;
        if (isset($data['seat_number'])) {
            $seatTaken = Ticket::whereHas('passenger.booking', function ($query) use ($flight) {
                    $query->where('flight_id', $flight->id);
                })
                ->where('seat_number', $data['seat_number'])
                ->exists();

            if ($seatTaken) {
                Log::channel('booking')->warning('Seat already taken', [
                    'flight_id' => $flight->id,
                    'seat_number' => $data['seat_number'],
                ]);

                return [
                    'success' => false,
                    'message' => "Seat {$data['seat_number']} is already taken on this flight."
                ];
            }
        }
        
        $ticket = Ticket::create($data);
        $ticket->load(['passenger']);

        return [
            'success' => true,
            'ticket' => $ticket
        ];
    }

    public function update(Ticket $ticket, array $data)
    {   
        $flight = $ticket->passenger->booking->flight;
        if (isset($data['seat_number'])) {
            $seatTaken = Ticket::whereHas('passenger.booking', function ($query) use ($flight) {
                    $query->where('flight_id', $flight->id);
                })
                ->where('seat_number', $data['seat_number'])
                ->exists();

            if ($seatTaken) {
                Log::channel('booking')->warning('Seat already taken', [
                    'flight_id' => $flight->id,
                    'seat_number' => $data['seat_number'],
                ]);

                return [
                    'success' => false,
                    'message' => "Seat {$data['seat_number']} is already taken on this flight."
                ];
            }
        }
        if (isset($data['ticket_number']) || isset($data['passenger_id'])) {
            Log::channel('booking')->warning('Trying to change ticket number OR passenger id', [
                    'flight_id' => $flight->id,
                ]);
            return [
                'success' => false,
                'message' => 'Ticket number and passenger cannot be changed.'
            ];
        }
        $ticket->update($data);
        $ticket->load(['passenger']);

        return [
            'success' => true,
            'ticket' => $ticket
        ];
    }

    public function delete(Ticket $ticket)
    {
        $ticket->delete();

        return [
            'success' => true
        ];
    }
    public function generatePdf(Ticket $ticket)
    {
        $ticket->load('passenger.booking.flight');

        Log::channel('booking')->info('Ticket pdf generated', [
            'ticket_id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
        ]);

        return Pdf::loadView('admin.tickets.pdf', compact('ticket'));
    }
}
