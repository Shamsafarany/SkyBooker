<?php

namespace App\Services\Admin;

use App\Contracts\PDFInterface;
use App\Models\Ticket;
use App\Models\Passenger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use \App\Http\Resources\Api\V1\TicketResource;
use Barryvdh\DomPDF\Facade\Pdf;


class TicketService
{
    public function __construct(private PDFInterface $pdfService) {}
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

        return $this->pdfService->download(
            'admin.tickets.pdf',
            ['ticket' => $ticket],
            'ticket-' . $ticket->ticket_number . '.pdf'
        );
    }

    public function createTicketForPassenger(Passenger $passenger, $seatNumber)
    {
        try {
            $ticket = Ticket::create([
                'passenger_id' => $passenger->id,
                'ticket_number' => Ticket::generateTicketNumber(),
                'first_name' => $passenger->first_name,
                'last_name' => $passenger->last_name,
                'email' => $passenger->email,
                'phone' => $passenger->phone,
                'seat_number' => $seatNumber,
                'class' => 'economy',
                'meal_preference' => $passenger->meal_preference ?? 'standard',
                'issued_at' => now(),
            ]);

            Log::channel('booking')->info('Ticket created', [
                'ticket_id' => $ticket->id,
                'passenger_id' => $passenger->id,
            ]);

            return $ticket;

        } catch (\Throwable $e) {
            Log::channel('booking')->error('Ticket creation failed', [
                'passenger_id' => $passenger->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Ticket creation failed.'
            ];
        }
    }
    public function deleteForPassenger(Passenger $passenger)
    {
        try {
            if ($passenger->ticket) {
                $passenger->ticket->delete();

                Log::channel('booking')->warning('Ticket deleted', [
                    'ticket_id' => $passenger->ticket->id,
                    'passenger_id' => $passenger->id,
                ]);
            }

            return ['success' => true];

        } catch (\Throwable $e) {
            Log::channel('booking')->error('Ticket delete failed', [
                'error' => $e->getMessage(),
                'passenger_id' => $passenger->id,
            ]);
            return [
                'success' => false,
                'message' => 'Ticket creation failed.'
            ];
        }
    }

    public function updateForPassenger(Passenger $passenger, array $data = []): ?Ticket
    {
        try {
            $ticket = $passenger->ticket;

            if (!$ticket) {
                Log::warning('No ticket found for passenger', [
                    'passenger_id' => $passenger->id
                ]);
                return null;
            }

            $updateData = [
                'first_name' => $passenger->first_name,
                'last_name'  => $passenger->last_name,
                'email'      => $passenger->email,
                'phone'      => $passenger->phone,
            ];

            // Optional fields
            if (isset($data['seat_number'])) {
                $updateData['seat_number'] = $data['seat_number'];
            }

            if (isset($data['meal_preference'])) {
                $updateData['meal_preference'] = $data['meal_preference'];
            }

            if (isset($data['class'])) {
                $updateData['class'] = $data['class'];
            }

            if (isset($data['special_requests'])) {
                $updateData['special_requests'] = $data['special_requests'];
            }

            $ticket->update($updateData);

            Log::info('Ticket updated for passenger', [
                'ticket_id'    => $ticket->id,
                'passenger_id' => $passenger->id,
                'updated_data' => $updateData
            ]);

            return $ticket;

        } catch (\Throwable $e) {
            Log::error('Ticket update failed', [
                'error'        => $e->getMessage(),
                'passenger_id' => $passenger->id,
            ]);

            return null;
        }
    }


    public function restoreForPassenger(Passenger $passenger)
    {
        try {
            if ($passenger->ticket && $passenger->ticket->trashed()) {
                $passenger->ticket->restore();

                Log::channel('booking')->info('Ticket restored', [
                    'ticket_id' => $passenger->ticket->id,
                    'passenger_id' => $passenger->id,
                ]);
            }

            return ['success' => true];

        } catch (\Throwable $e) {
            Log::channel('booking')->error('Ticket restore failed', [
                'error' => $e->getMessage(),
                'passenger_id' => $passenger->id,
            ]);

            return ['success' => false];
        }
    }

}
