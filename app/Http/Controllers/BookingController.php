<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flight;
use App\Models\User;
use App\Models\Booking;

class BookingController extends Controller
{

    private function getBookings(){
        $flights = Flight::with([
            'bookings.user',
            'bookings.passengers',
            'origin',
            'destination',
            'airline',
        ])->orderBy('departure_date')->paginate(10);
        return $flights;
    }

    public function index()
    {
        $flights = $this->getBookings();
        return view('admin.bookings.index', compact('flights'));
    }

    public function create()
    {
    $users = User::orderBy('first_name')->get();
    $flights = Flight::with(['origin', 'destination'])
        ->where('departure_date', '>=', now())
        ->orderBy('departure_date')
        ->get();
    $bookingReference = Booking::generateReference();
    return view('admin.bookings.create', compact('users', 'flights', 'bookingReference'));
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

    public function destroy(string $id)
    {
        //
    }
}
