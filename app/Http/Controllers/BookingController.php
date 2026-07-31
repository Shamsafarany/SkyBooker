<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{

    private function getBookings(){
        $bookings = Booking::with([
            'user',
            'flight.airline',
            'flight.origin',
            'flight.destination',
            'passengers',
            'passengers.ticket',
        ])->paginate(15); ;
        return $bookings;
    }

    public function index()
    {
        $bookings = $this->getBookings();
        return view('admin.bookings.index', compact('bookings'));
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

    public function destroy(string $id)
    {
        //
    }
}
