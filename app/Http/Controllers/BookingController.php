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
        $bookings = Booking::all();
        $stats = [
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
        return view('admin.bookings.index', compact('flights', 'stats'));
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
