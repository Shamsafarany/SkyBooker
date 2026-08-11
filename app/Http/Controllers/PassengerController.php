<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Passenger;

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
        //
    }
    public function store(Request $request)
    {
        //
    }
    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }
    public function destroy(Passenger $passenger)
    {
        $booking = $passenger->booking_id;
        $booking->decrement('number_of_seats', 1);
        $passenger->delete();
        return redirect()->back()->with('success', 'Passenger removed successfully!');
    }
}
