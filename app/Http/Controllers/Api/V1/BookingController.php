<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BookingCollection;
use App\Http\Resources\Api\V1\PassengerCollection;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\BookingResource;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Http\Resources\Api\V1\TicketCollection;
use App\Models\Ticket;

class BookingController extends Controller
{

    
    public function index()
    {
        $bookings = Booking::with(['user', 'flight', 'passengers'])->paginate(15);
        return new BookingCollection($bookings);
    }

    public function store(StoreBookingRequest $request)
    {
        $booking = Booking::create($request->validated());
        $booking->load(['user', 'flight', 'passengers.ticket']);
        
        return response()->json(
            new BookingResource($booking),
            201
        );
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'flight', 'passengers']);
        return new BookingResource($booking);
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $booking->update($request->validated());
        $booking->load(['user', 'flight']);
        return new BookingResource($booking);
    }
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->json(null, 204);
    }
    public function passengers(Booking $booking)
    {
        $passengers = $booking->passengers()
            ->with(['ticket'])
            ->paginate(15);
        
        return new PassengerCollection($passengers);
    }
    public function tickets(Booking $booking)
    {
        $tickets = Ticket::whereHas('passenger', function ($query) use ($booking) {
            $query->where('booking_id', $booking->id);
        })->with(['passenger'])->paginate(15);
        
        return new TicketCollection($tickets);
    }
    }
