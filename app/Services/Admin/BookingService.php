<?php

namespace App\Services\Admin;

use App\Models\Booking;
use App\Models\Flight;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Mail\BookingCreated;
use App\Http\Resources\Api\V1\BookingResource;

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

            Mail::to($booking->user->email)->send(new BookingCreated($booking));

            return [
                'success' => true,
                'booking' => $booking
            ];

        } catch (\Throwable $e) {
            Log::channel('booking')->error('Booking creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $validated,
            ]);

            return [
                'success' => false,
                'error' => 'Failed to create booking. Please try again.'
            ];
        }
    }

    public function update(Booking $booking, array $validated)
    {
        try {
            $oldFlight = $booking->flight;
            $newFlight = Flight::findOrFail($validated['flight_id']);

            Cache::forget('admin.bookings.stats');
            Log::channel('booking')->info('Stats cleared.');

            $flightChanged = $booking->flight_id != $validated['flight_id'];

            if ($flightChanged) {
                $oldFlight->increment('available_seats', $booking->number_of_seats);
                $oldFlight->decrement('booked_seats', $booking->number_of_seats);

                if ($newFlight->available_seats < $validated['number_of_seats']) {
                    return [
                        'success' => false,
                        'error' => 'Not enough seats on new flight! Only ' . $newFlight->available_seats . ' seats available.'
                    ];
                }

                $newFlight->decrement('available_seats', $validated['number_of_seats']);
                $newFlight->increment('booked_seats', $validated['number_of_seats']);
            }

            // Seat count change
            if ($booking->number_of_seats != $validated['number_of_seats']) {
                $difference = $validated['number_of_seats'] - $booking->number_of_seats;

                if ($difference > 0) {
                    if ($newFlight->available_seats < $difference) {
                        return [
                            'success' => false,
                            'error' => 'Not enough additional seats! Only ' . $newFlight->available_seats . ' more seats available.'
                        ];
                    }

                    $newFlight->decrement('available_seats', $difference);
                    $newFlight->increment('booked_seats', $difference);

                } else {
                    $returnSeats = abs($difference);
                    $newFlight->increment('available_seats', $returnSeats);
                    $newFlight->decrement('booked_seats', $returnSeats);

                    if ($booking->passengers()->count() > $validated['number_of_seats']) {
                        return [
                            'success' => false,
                            'error' => 'Cannot reduce seats below passenger count.'
                        ];
                    }
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
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'booking_id' => $booking->id,
            ]);

            return [
                'success' => false,
                'error' => 'Failed to update booking.'
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
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Failed to delete booking.'
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

        return ['success' => true];
    }

    public function getApiList(){
        try {
            $bookings = Booking::with(['user', 'flight', 'passengers.ticket'])
                ->paginate(15);

            return [
                'success' => true,
                'bookings' => $bookings
            ];

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

}
