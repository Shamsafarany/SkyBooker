<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Http\Resources\Api\V1\BookingResource;
use App\Http\Resources\Api\V1\BookingCollection;
use App\Http\Resources\Api\V1\PassengerCollection;
use App\Http\Resources\Api\V1\TicketCollection;
use App\Services\Admin\BookingService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}
    public function index(Request $request)
    {
        try {
            $result = $this->bookingService->getApiList($request); 
            return Response::success($result, 'Bookings retrieved');

        } catch (\Throwable $e) {
            Log::error('BOOKING INDEX ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve bookings', 500);
        }
    }

    public function store(StoreBookingRequest $request)
    {

        try {
            $result = $this->bookingService->create($request->validated());
            if (!$result['success']) {
                return Response::error($result['message'], 422);
            }
            return Response::success(new BookingResource($result['booking']), 'Booking created', 201);

        } catch (\Throwable $e) {
            Log::error('BOOKING STORE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to create booking', 500);
        }
    }

    public function show(Booking $booking)
    {
        try{
            $result = $this->bookingService->getApiShow($booking);

            if (!$result['success']) {
                return Response::error($result['message'], 500);
            }

            return Response::success(
                $result['booking'],
                'Booking retrieved'
            );
        } catch (ModelNotFoundException $e) {
            return Response::error('Booking not found.', 404);
        }
    }


    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        try {
            $result = $this->bookingService->update($booking, $request->validated());
            if (!$result['success']) {
                return Response::error($result['message'], 400);
            }
            return Response::success(new BookingResource($result['booking']), 'Booking updated');

        } catch (\Throwable $e) {
            Log::error('BOOKING UPDATE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to update booking', 500);
        }
    }

    public function destroy(Booking $booking)
    {
        try {
            $result = $this->bookingService->delete($booking);
            if (!$result['success']) {
                return Response::error($result['message'], 400);
            }

            return Response::success(null, 'Booking deleted', 204);

        } catch (\Throwable $e) {
            Log::error('BOOKING DELETE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to delete booking', 500);
        }
    }

    public function passengers(Booking $booking)
    {
        try {
            $result = $this->bookingService->getApiPassengers($booking);

            if (!$result['success']) {
                return Response::error($result['message'], 500);
            }

            return Response::success(
                new PassengerCollection($result['passengers']),
                'Booking passengers retrieved'
            );

        } catch (\Throwable $e) {
            Log::error('BOOKING PASSENGERS ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve booking passengers', 500);
        }
    }

    public function tickets(Booking $booking)
    {
        try {
            $result = $this->bookingService->getApiTickets($booking);

            if (!$result['success']) {
                return Response::error($result['message'], 500);
            }

            return Response::success(
                new TicketCollection($result['tickets']),
                'Booking tickets retrieved'
            );

        } catch (\Throwable $e) {
            Log::error('BOOKING TICKETS ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve booking tickets', 500);
        }
    }

    public function restore($id)
    {
        try {
            $booking = Booking::withTrashed()->findOrFail($id);
            
            $result = $this->bookingService->restore($booking);

            if (!$result['success']) {
                return Response::error($result['message'], 400);
            }

            return Response::success(
                new BookingResource($result['booking']->load(['user', 'flight', 'passengers']))
            );

        } catch (ModelNotFoundException $e) {
            return Response::error('Booking not found.', 404);
        } catch (\Throwable $e) {
            Log::error('Booking restore error:', [
                'booking_id' => $id,
                'error' => $e->getMessage()
            ]);
            return Response::error('Failed to restore booking.', 500);
        }
    }

    public function archive()
    {
        try {
            $result = $this->bookingService->getArchivedBookings();

            if (!$result['success']) {
                return Response::error($result['message'], 400);
            }

            return Response::success(
                BookingResource::collection($result['bookings']),
                'Trashed bookings retrieved successfully'
            );

        } catch (\Throwable $e) {
            Log::error('Get trashed bookings error:', [
                'error' => $e->getMessage()
            ]);

            return Response::error('Failed to get trashed bookings.', 500);
        }
    }
    
}
