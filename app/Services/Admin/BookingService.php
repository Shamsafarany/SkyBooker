<?php

namespace App\Services\Admin;

use App\Filters\BookingFilter;
use App\Http\Resources\Api\V1\BookingCollection;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Cache;

use App\Http\Resources\Api\V1\BookingResource;
use Illuminate\Http\Request;

class BookingService
{
    public function __construct(
        private PassengerService $passengerService,
        private TicketService $ticketService
    ) {}

    public function getBookings()
    {
        return Flight::with([
            'bookings.user',
            'bookings.passengers',
            'bookings.passengers.ticket',
            'origin',
            'destination',
            'airline',
        ])->orderBy('departure_date')->paginate(10);
    }

    public function getBookingsWithStats()
    {
        $bookings = Booking::all();

        return Cache::remember('admin.bookings.stats', 60, function () use ($bookings) {
            return [
                'total' => $bookings->count(),
                'pending' => $bookings->where('status', 'pending')->count(),
                'confirmed' => $bookings->where('status', 'confirmed')->count(),
                'cancelled' => $bookings->where('status', 'cancelled')->count(),
                'completed' => $bookings->where('status', 'completed')->count(),
                'failed' => $bookings->where('status', 'failed')->count(),
                'refunded' => $bookings->where('status', 'refunded')->count(),
                'total_revenue' => $bookings->sum('total_price'),
                'total_seats' => $bookings->sum('number_of_seats'),
                'today' => $bookings->where('booking_date', '>=', now()->startOfDay())->count(),
                'this_week' => $bookings->where('booking_date', '>=', now()->startOfWeek())->count(),
                'this_month' => $bookings->where('booking_date', '>=', now()->startOfMonth())->count(),
                'average_seats' => $bookings->avg('number_of_seats') ? round($bookings->avg('number_of_seats'), 1) : 0,
                'average_price' => $bookings->avg('total_price') ? round($bookings->avg('total_price'), 2) : 0,
            ];
        });
    }

    public function create(array $validated)
    {
        try {
            $flight = Flight::findOrFail($validated['flight_id']);

            $existingBooking = Booking::where('user_id', $validated['user_id'])
                    ->where('flight_id', $validated['flight_id'])
                    ->whereNotIn('status', ['cancelled', 'completed']) 
                    ->first();

                if ($existingBooking) {
                    Log::channel('booking')->warning('Duplicate booking attempt', [
                        'user_id' => $validated['user_id'],
                        'flight_id' => $validated['flight_id'],
                        'existing_booking_id' => $existingBooking->id,
                        'existing_booking_status' => $existingBooking->status
                    ]);

                    return [
                        'success' => false,
                        'message' => 'You already have a booking on this flight. Each user can only book once per flight.',
                        'existing_booking' => $existingBooking
                    ];
                }

            if ($flight->available_seats < $validated['number_of_seats']) {
                return [
                    'success' => false,
                    'error' => 'Not enough seats available! Only ' . $flight->available_seats . ' seats left.'
                ];
            }

            $booking = Booking::create($validated);

            Cache::forget('admin.bookings.stats');
            Log::channel('booking')->info('Stats cleared.');

            $flight->decrement('available_seats', $validated['number_of_seats']);
            $flight->increment('booked_seats', $validated['number_of_seats']);

            Log::channel('booking')->info('Booking created', [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
                'total_price' => $booking->total_price,
                'status' => $booking->status,
                'customer_id' => $validated['user_id'],
                'flight_id' => $flight->id,
            ]);

            return [
                'success' => true,
                'booking' => $booking
            ];

        } catch (\Throwable $e) {
            Log::channel('booking')->error('Booking creation failed', [
                'message' => $e->getMessage(),
                'data' => $validated,
            ]);

            return [
                'success' => false,
                'message' => 'Failed to create booking. Please try again.'
            ];
        }
    }

    public function update(Booking $booking, array $validated)
    {
        try {
            
            Cache::forget('admin.bookings.stats');
            Log::channel('booking')->info('Stats cleared.');

            if ($this->hasFlightChange($booking, $validated)) {
            $result = $this->processFlightChange($booking, $validated);
                if (!$result['success']) {
                    return $result;
                }
                $newFlight = $result['flight'];
            }

            if ($this->hasSeatChange($booking, $validated)) {
                $targetFlight = $this->getTargetFlight($booking, $validated, $newFlight ?? null);
                $result = $this->processSeatChange($booking, $validated, $targetFlight);
                if (!$result['success']) {
                    return $result;
                }
            }

            $booking->update($validated);
            Log::channel('booking')->info('Booking updated', [
                'booking_id' => $booking->id,
                'changes' => $booking->getChanges(),
            ]);

            return [
                'success' => true,
                'booking' => $booking
            ];

        } catch (\Throwable $e) {
            Log::channel('booking')->error('Booking update failed', [
                'message' => $e->getMessage(),
                'booking_id' => $booking->id,
            ]);

            return [
                'success' => false,
                'message' => 'Failed to update booking.'
            ];
        }
    }

    public function delete(Booking $booking)
    {
        try {
            foreach ($booking->passengers as $passenger) {
                $this->ticketService->deleteForPassenger($passenger);
                $this->passengerService->deleteForBooking($passenger);
            }

            Cache::forget('admin.bookings.stats');
            Log::channel('booking')->warning('Booking deleted', [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
            ]);

            if ($booking->trashed()) {
                $booking->forceDelete();
                    Log::channel('booking')->warning('Booking deleted permanently.', [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                ]);
            } else {
                $booking->delete();
            }

            return ['success' => true];

        } catch (\Throwable $e) {
            Log::channel('booking')->error('Booking delete failed', [
                'booking_id' => $booking->id,
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to delete booking.'
            ];
        }
    }

    public function restore(Booking $booking)
    {
        $booking = Booking::withTrashed()->findOrFail($booking->id);
        $booking->restore();

        Cache::forget('admin.bookings.stats');
        Log::channel('booking')->info('Stats cleared.');
        Log::channel('booking')->warning('Booking restored.');

        foreach ($booking->passengers()->withTrashed()->get() as $passenger) {
            $this->passengerService->restoreForBooking($passenger);
            $this->ticketService->restoreForPassenger($passenger);
        }

        return ['success' => true,
                'booking' => $booking];
    }

    public function getApiList(Request $request){
        try {
            $query = Booking::query()
            ->with(['user', 'flight', 'passengers.ticket']);

        $query = (new BookingFilter())->apply($query, $request);

        if ($request->filled('sort')) {
            $direction = $request->input('direction', 'asc');
            $query->orderBy($request->sort, $direction);
        }

        $perPage = $request->input('paginate')
        ?? $request->input('per_page')
        ?? $request->input('limit')
        ?? $request->input('perPage');

        if (!is_numeric($perPage) || $perPage <= 0) {
            $perPage = 15;
        }

        $bookings = $query->paginate((int) $perPage);
        
        return new BookingCollection($bookings);
        } catch (\Throwable $e) {
            Log::error('BOOKING API LIST ERROR', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Failed to retrieve bookings'
            ];
        }
    }

    public function getApiShow(Booking $booking)
    {
        try {
            $key = "api.bookings.show.{$booking->id}";

            $data = Cache::remember($key, 60, function () use ($booking) {
                Log::info("BOOKING SHOW: Cache MISS for ID {$booking->id}");

                $booking->load(['user', 'flight', 'passengers.ticket']);

                return (new BookingResource($booking))->resolve();
            });

            Log::info("BOOKING SHOW: Cache HIT for ID {$booking->id}");

            return [
                'success' => true,
                'booking' => $data
            ];

        } catch (\Throwable $e) {
            Log::error('BOOKING API SHOW ERROR', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id,
            ]);

            return [
                'success' => false,
                'message' => 'Failed to retrieve booking'
            ];
        }
    }

    public function getApiPassengers(Booking $booking)
    {
        try {
            $passengers = $booking->passengers()
                ->with(['ticket'])
                ->paginate(15);

            return [
                'success' => true,
                'passengers' => $passengers
            ];

        } catch (\Throwable $e) {
            Log::error('BOOKING API PASSENGERS ERROR', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id,
            ]);

            return [
                'success' => false,
                'message' => 'Failed to retrieve booking passengers'
            ];
        }
    }

    public function getApiTickets(Booking $booking)
    {
        try {
            $tickets = Ticket::whereHas('passenger', function ($query) use ($booking) {
                    $query->where('booking_id', $booking->id);
                })
                ->with(['passenger'])
                ->paginate(15);

            return [
                'success' => true,
                'tickets' => $tickets
            ];

        } catch (\Throwable $e) {
            Log::error('BOOKING API TICKETS ERROR', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id,
            ]);

            return [
                'success' => false,
                'message' => 'Failed to retrieve booking tickets'
            ];
        }
    }

    private function hasFlightChange(Booking $booking, array $validated): bool
{
    return isset($validated['flight_id']) && $booking->flight_id != $validated['flight_id'];
}

private function hasSeatChange(Booking $booking, array $validated): bool
{
    return isset($validated['number_of_seats']) && $booking->number_of_seats != $validated['number_of_seats'];
}

private function getTargetFlight(Booking $booking, array $validated, $newFlight = null): Flight
{
    return isset($validated['flight_id']) ? $newFlight : $booking->flight;
}

private function isCancelled(Booking $booking, array $validated): bool
{
    return isset($validated['status']) && $validated['status'] === 'cancelled' && $booking->status === 'cancelled';
}

private function processFlightChange(Booking $booking, array $validated): array
{
    $oldFlight = $booking->flight;
    $newFlight = Flight::findOrFail($validated['flight_id']);

    // Return seats to old flight
    $oldFlight->increment('available_seats', $booking->number_of_seats);
    $oldFlight->decrement('booked_seats', $booking->number_of_seats);

    // Use provided seat count or existing
    $seatsToBook = $validated['number_of_seats'] ?? $booking->number_of_seats;

    // Check new flight availability
    if ($newFlight->available_seats < $seatsToBook) {
        // Rollback
        $oldFlight->decrement('available_seats', $booking->number_of_seats);
        $oldFlight->increment('booked_seats', $booking->number_of_seats);

        return [
            'success' => false,
            'message' => 'Not enough seats on new flight! Only ' . $newFlight->available_seats . ' seats available.'
        ];
    }

    $newFlight->decrement('available_seats', $seatsToBook);
    $newFlight->increment('booked_seats', $seatsToBook);

    return [
        'success' => true,
        'flight' => $newFlight
    ];
}

private function processSeatChange(Booking $booking, array $validated, Flight $targetFlight): array
{
    $difference = $validated['number_of_seats'] - $booking->number_of_seats;

    if ($difference > 0) {
        // More seats - check availability
        if ($targetFlight->available_seats < $difference) {
            return [
                'success' => false,
                'message' => 'Not enough additional seats! Only ' . $targetFlight->available_seats . ' more seats available.'
            ];
        }

        $targetFlight->decrement('available_seats', $difference);
        $targetFlight->increment('booked_seats', $difference);

    } else {
        // Less seats - return them
        $returnSeats = abs($difference);
        $targetFlight->increment('available_seats', $returnSeats);
        $targetFlight->decrement('booked_seats', $returnSeats);

        // Check if passengers exceed new seat count
        $passengerCount = $booking->passengers()->count();
        if ($passengerCount > $validated['number_of_seats']) {
            // Rollback
            $targetFlight->decrement('available_seats', $returnSeats);
            $targetFlight->increment('booked_seats', $returnSeats);

            return [
                'success' => false,
                'message' => 'Cannot reduce seats below passenger count (' . $passengerCount . ' passengers).'
            ];
        }
    }

    return ['success' => true];
}
    public function getArchivedBookings(): array
        {
            try {
                $bookings = Booking::onlyTrashed()
                    ->with(['user', 'flight', 'passengers'])
                    ->latest('deleted_at')->get();

                $stats = [
                    'total_trashed' => Booking::onlyTrashed()->count(),
                    'today_trashed' => Booking::onlyTrashed()
                        ->whereDate('deleted_at', today())
                        ->count(),
                    'this_week_trashed' => Booking::onlyTrashed()
                        ->whereDate('deleted_at', '>=', now()->startOfWeek())
                        ->count(),
                ];

                return [
                    'success' => true,
                    'bookings' => $bookings,
                    'stats' => $stats
                ];

            } catch (\Throwable $e) {
                Log::channel('booking')->error('Failed to get trashed bookings', [
                    'message' => $e->getMessage()
                ]);

                return [
                    'success' => false,
                    'message' => 'Failed to get trashed bookings.'
                ];
            }
        }


    public function getLatestBookings(int $limit = 5)
{
    Log::channel('booking')->info('Fetching latest bookings from database');

    return Booking::with(['user', 'flight', 'flight.origin', 'flight.destination'])
        ->latest()
        ->limit($limit)
        ->get()
        ->map(function ($booking) {
            return [
                'id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
                'user_name' => $booking->user->getFullNameAttribute() ?? 'N/A',
                'user_email' => $booking->user->email ?? 'N/A',
                'flight_number' => $booking->flight->flight_number ?? 'N/A',
                'origin' => $booking->flight->origin->code ?? 'N/A',
                'destination' => $booking->flight->destination->code ?? 'N/A',
                'total_price' => (float) $booking->total_price,
                'passenger_count' => (int) $booking->passengers->count(),
                'status' => $booking->status,
                'status_raw' => $booking->status,
                'booked_at' => $booking->created_at,
                'booked_ago' => $booking->created_at->diffForHumans(),
            ];
        });
}


}
