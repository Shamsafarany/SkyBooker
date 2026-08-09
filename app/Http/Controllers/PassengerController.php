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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Passenger $passenger)
    {
        //$passenger->delete();
        //return redirect()->back()->with('success', 'Passenger removed successfully!');
    }
}
