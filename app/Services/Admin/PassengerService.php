<?php

namespace App\Services\Admin;

use App\Models\Passenger;
use App\Models\Booking;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Api\V1\PassengerResource;

class PassengerService
{
    public function getAllWithStats()
    {
        $passengers = Passenger::with(['booking', 'ticket'])->get();

        $stats = Cache::remember('admin.passengers.stats', 60, function () use ($passengers) {
            return [
                'total' => $passengers->count(),
                'registered' => $passengers->filter(fn($p) => $p->userExists())->count(),
                'guest' => $passengers->filter(fn($p) => !$p->userExists())->count(),
                'by_status' => [
                    'pending' => $passengers->where('status', 'pending')->count(),
                    'confirmed' => $passengers->where('status', 'confirmed')->count(),
                    'checked_in' => $passengers->where('status', 'checked_in')->count(),
                    'boarded' => $passengers->where('status', 'boarded')->count(),
                    'cancelled' => $passengers->where('status', 'cancelled')->count(),
                ],
                'by_nationality' => $passengers->whereNotNull('nationality')
                    ->groupBy('nationality')
                    ->map->count()
                    ->sortDesc()
                    ->take(5),
                'total_seats_booked' => $passengers->sum(fn($p) => $p->booking->number_of_seats ?? 0),
                'average_age' => round($passengers->whereNotNull('date_of_birth')->avg(fn($p) => $p->date_of_birth?->age) ?? 0),
                'with_passport' => $passengers->whereNotNull('passport_number')->count(),
                'with_phone' => $passengers->whereNotNull('phone')->count(),
            ];
        });

        Log::channel('booking')->info('Stats are cached.');

        return compact(['passengers', 'stats']);
    }

    public function create(array $data)
    {
        try {
            $passenger = Passenger::create($data);

            $booking = $passenger->booking;
            $flight = $booking->flight;

            // Update booking seat count
            $booking->increment('number_of_seats', 1);

            // Update booking total price
            $booking->update([
                'total_price' => $booking->passengers()->count() * $flight->price
            ]);

            // Update flight seat counts
            $flight->decrement('available_seats');
            $flight->increment('booked_seats');

            // Create ticket
            $ticket = $this->createTicket($passenger, $data['seat_number'] ?? null);

            Cache::forget('admin.passengers.stats');
            Log::channel('booking')->info('Stats cleared.');

            Log::channel('booking')->info('Passenger created', [
                'passenger_id' => $passenger->id,
                'booking_id' => $booking->id,
            ]);

            return [
                'success' => true,
                'passenger' => $passenger,
                'ticket' => $ticket,
                'booking' => $booking
            ];

        } catch (\Throwable $e) {
            Log::channel('booking')->error('Passenger creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data,
            ]);

            return [
                'success' => false,
                'message' => 'Failed to create passenger.',
                'error' => $e->getMessage()
            ];
        }
    }

    public function update(Passenger $passenger, array $data)
    {
        $passenger->update($data);

        Cache::forget('admin.passengers.stats');
        Log::channel('booking')->info('Stats cleared.');

        if ($passenger->ticket) {
            $passenger->ticket->update([
                'first_name' => $passenger->first_name,
                'last_name' => $passenger->last_name,
                'email' => $passenger->email,
                'phone' => $passenger->phone,
                'seat_number' => $data['seat_number'] ?? $passenger->ticket->seat_number,
                'meal_preference' => $data['meal_preference'] ?? $passenger->ticket->meal_preference,
            ]);
        }

        Log::channel('booking')->info('Passenger updated', [
            'passenger_id' => $passenger->id
        ]);

        return [
            'success' => true,
            'passenger' => $passenger
        ];
    }

    public function delete(Passenger $passenger)
    {
        $booking = $passenger->booking;
        $flight = $booking->flight;

        Cache::forget('admin.passengers.stats');
        Log::channel('booking')->info('Stats cleared.');

        if ($passenger->ticket) {
            $passenger->ticket->delete();
        }

        $booking->decrement('number_of_seats', 1);
        $passenger->delete();

        $booking->update([
            'total_price' => $booking->passengers()->count() * $flight->price
        ]);

        $flight->increment('available_seats');
        $flight->decrement('booked_seats');

        if ($booking->passengers()->count() === 0) {
            $booking->update([
                'status' => 'cancelled',
                'number_of_seats' => 0,
                'total_price' => 0,
            ]);
        }

        Log::channel('booking')->warning('Passenger deleted', [
            'passenger_id' => $passenger->id,
            'booking_id' => $booking->id,
        ]);

        return [
            'success' => true
        ];
    }

    protected function createTicket(Passenger $passenger, $seatNumber)
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

            throw $e;
        }
    }

    public function getApiList(){
        $passengers = Passenger::with(['booking', 'ticket'])
                ->paginate(15);
        Log::info('PASSENGER INDEX: Cache MISS - querying database'); 
        return [
            'success' => true,
            'passengers' => $passengers
        ]; 
    }

    public function getApiShow(Passenger $passenger)
    {
        try {
            $key = "api.passengers.show.{$passenger->id}";

            $data = Cache::remember($key, 60, function () use ($passenger) {
                Log::info('PASSENGER SHOW: Cache MISS - querying database');

                $passenger->load(['booking', 'ticket']);

                return (new PassengerResource($passenger))->resolve();
            });

            Log::info('PASSENGER SHOW: Cache HIT - getting cache');

            return [
                'success' => true,
                'passenger' => $data
            ];

        } catch (\Throwable $e) {
            Log::error('PASSENGER SHOW ERROR', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Failed to retrieve passenger'
            ];
        }
    }

    public function getApiTicket(Passenger $passenger){
        try {
            $ticket = $passenger->ticket()->with(['passenger'])->first();

            if (!$ticket) {
                return [
                    'success' => false,
                    'message' => 'No ticket found for this passenger'
                ];
            }

            return [
                'success' => true,
                'ticket' => $ticket
            ];

        } catch (\Throwable $e) {
            Log::error('PASSENGER TICKET ERROR', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Failed to retrieve passenger ticket'
            ];
        }
    }

    public function getApiBooking(Passenger $passenger){
        try{
            $booking = $passenger->booking()->with(['flight', 'user'])->first();

            if (!$booking) {
                return [
                    'success' => false,
                    'message' => 'No booking found for this passenger'
                ];
            }

            return [
                'success' => true,
                'booking' => $booking
            ];

        } catch (\Throwable $e) {
            Log::error('PASSENGER BOOKING ERROR', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Failed to retrieve passenger ticket'
            ];
        }
    }
}
