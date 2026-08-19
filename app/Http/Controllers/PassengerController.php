<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Passenger;
use App\Models\Booking;
use App\Http\Requests\Passenger\StorePassengerRequest;
use App\Http\Requests\Passenger\UpdatePassengerRequest;

class PassengerController extends Controller
{
    public function index()
    {
        $passengers = Passenger::all();
        $stats = [
            'total' => $passengers->count(),
            'registered' => $passengers->filter(function ($passenger) {
                return User::where('email', $passenger->email)->exists();
            })->count(),
            'guest' => $passengers->filter(function ($passenger) {
                return !User::where('email', $passenger->email)->exists();
            })->count(),
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
            'total_seats_booked' => $passengers->sum(function ($passenger) {
                return $passenger->booking->number_of_seats ?? 0;
            }),
            'average_age' => $passengers->whereNotNull('date_of_birth')
                ->avg(function ($passenger) {
                    return $passenger->date_of_birth?->age;
                }) ? round($passengers->whereNotNull('date_of_birth')->avg(function ($passenger) {
                    return $passenger->date_of_birth?->age;
                })) : 0,
            'with_passport' => $passengers->whereNotNull('passport_number')->count(),
            'with_phone' => $passengers->whereNotNull('phone')->count(),
        ];

        return view('admin.passengers.index', compact('passengers', 'stats'));
    }

    public function create()
    {
        $bookings = Booking::with(['user', 'flight'])->get();
        return view('admin.passengers.create', compact('bookings'));
    }
    public function store(StorePassengerRequest $request)
    {
        $validated = $request->validated();
        $passenger = Passenger::create($validated);
        $booking = $passenger->booking_id();
        $booking->increment('number_of_seats', 1);
        return redirect()->route('admin.bookings.show', $booking->id)
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
        $validated = $request->validated();
        $passenger->update($validated);
        return redirect()->route('admin.bookings.show', $passenger->booking_id)->with('success', 'Passenger "' . $passenger->id . '" updated successfully!');
    }
    public function destroy(Passenger $passenger)
    {
        $booking = $passenger->booking_id;
        $booking->decrement('number_of_seats', 1);
        $passenger->delete();
        return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Passenger removed successfully!');
    }
}
