<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Passenger;
use App\Models\Booking;
use App\Http\Requests\Passenger\StorePassengerRequest;
use App\Http\Requests\Passenger\UpdatePassengerRequest;
use App\Services\Admin\PassengerService;

class PassengerController extends Controller
{
    public function __construct(private PassengerService $passengerService) {}
    public function index()
    {
        $data = $this->passengerService->getAllWithStats();
        return view('admin.passengers.index', compact($data['passengers'], $data['stats']));
    }

    public function create(Request $request)
    {
        $bookingId = $request->query('booking_id');
        $booking = Booking::with(['flight', 'user'])->findOrFail($bookingId);
        return view('admin.passengers.create', compact('booking'));
    }
    public function store(StorePassengerRequest $request)
    {
        $result = $this->passengerService->create($request->validated());

        if (!$result['success']) {
            return back()->with('error', $result['message'])->withInput();
        }

        return redirect()->route('admin.bookings.show', $result['booking']->id)
            ->with('success', 'Passenger added successfully!');
    }


    public function show(Passenger $passenger)
    {
        $passenger->load(['booking', 'ticket']);
        return redirect()->route('admin.bookings.show', $passenger->booking_id);
    }

    public function edit(Passenger $passenger)
    {
        $bookings = Booking::with(['user', 'flight'])->get();
        return view('admin.passengers.edit', compact('passenger', 'bookings'));
    }

    public function update(UpdatePassengerRequest $request, Passenger $passenger)
    {
        $result = $this->passengerService->update($passenger, $request->validated());

        return redirect()->route('admin.bookings.show', $passenger->booking_id)
            ->with('success', 'Passenger updated successfully!');
    }

    public function destroy(Passenger $passenger)
    {
        $result = $this->passengerService->delete($passenger);

        return redirect()->route('admin.bookings.show', $passenger->booking_id)
            ->with('success', 'Passenger removed successfully!');
    }


}
