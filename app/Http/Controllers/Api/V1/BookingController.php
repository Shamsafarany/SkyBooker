<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Ticket;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Http\Resources\Api\V1\BookingResource;
use App\Http\Resources\Api\V1\BookingCollection;
use App\Http\Resources\Api\V1\PassengerCollection;
use App\Http\Resources\Api\V1\TicketCollection;

class BookingController extends Controller
{
    public function index()
    {
        try {
            $bookings = Booking::with(['user', 'flight', 'passengers'])
                ->paginate(15);
            Log::info('BOOKING INDEX: Cache MISS - querying database');  
            return Response::success(new BookingCollection($bookings), 'Bookings retrieved');

        } catch (\Throwable $e) {
            Log::error('BOOKING INDEX ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve bookings', 500);
        }
    }

    public function store(StoreBookingRequest $request)
    {
        try {
            $booking = Booking::create($request->validated());
            $booking->load(['user', 'flight', 'passengers.ticket']);

            return Response::success(new BookingResource($booking), 'Booking created', 201);

        } catch (\Throwable $e) {
            Log::error('BOOKING STORE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to create booking', 500);
        }
    }

    public function show(Booking $booking)
    {
        try {
            $key = "api.bookings.show.{$booking->id}";

            $data = Cache::remember($key, 60, function () use ($booking) {
                Log::info('BOOKING SHOW: Cache MISS - querying database');

                $booking->load(['user', 'flight', 'passengers.ticket']);

                return (new BookingResource($booking))->resolve();
            });

            Log::info('BOOKING SHOW: Cache HIT - getting cache');

            return Response::success($data, 'Booking retrieved');

        } catch (\Throwable $e) {
            Log::error('BOOKING SHOW ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve booking', 500);
        }
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        try {
            $booking->update($request->validated());
            $booking->load(['user', 'flight']);

            return Response::success(new BookingResource($booking), 'Booking updated');

        } catch (\Throwable $e) {
            Log::error('BOOKING UPDATE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to update booking', 500);
        }
    }

    public function destroy(Booking $booking)
    {
        try {
            $booking->delete();

            return Response::success(null, 'Booking deleted', 204);

        } catch (\Throwable $e) {
            Log::error('BOOKING DELETE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to delete booking', 500);
        }
    }

    public function passengers(Booking $booking)
    {
        try {
            $passengers = $booking->passengers()
                ->with(['ticket'])
                ->paginate(15);

            return Response::success(new PassengerCollection($passengers), 'Booking passengers retrieved');

        } catch (\Throwable $e) {
            Log::error('BOOKING PASSENGERS ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve booking passengers', 500);
        }
    }

    public function tickets(Booking $booking)
    {
        try {
            $tickets = Ticket::whereHas('passenger', function ($query) use ($booking) {
                $query->where('booking_id', $booking->id);
            })
            ->with(['passenger'])
            ->paginate(15);

            return Response::success(new TicketCollection($tickets), 'Booking tickets retrieved');

        } catch (\Throwable $e) {
            Log::error('BOOKING TICKETS ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve booking tickets', 500);
        }
    }
}
