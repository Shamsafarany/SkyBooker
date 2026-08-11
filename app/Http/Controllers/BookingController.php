<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flight;
use App\Models\User;
use App\Models\Booking;
use App\Models\Passenger;
use App\Models\Ticket;
use Illuminate\Validation\Rule;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\booking\UpdateBookingRequest;

class BookingController extends Controller
{

    private function getBookings(){
        $flights = Flight::with([
            'bookings.user',
            'bookings.passengers',
            'bookings.passengers.ticket',
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

    public function store(StoreBookingRequest $request)
    {
        $validated = $request->validated();

        // Get the flight
        $flight = Flight::findOrFail($validated['flight_id']);

        // Check seat availability
        if ($flight->available_seats < $validated['number_of_seats']) {
            return back()
                ->with('error', 'Not enough seats available! Only ' . $flight->available_seats . ' seats left.')
                ->withInput();
        }

        $booking = Booking::create($validated);
        foreach ($validated['passengers'] as $passengerData) {
        $passenger = Passenger::create([
            'booking_id' => $booking->id,
            'first_name' => $passengerData['first_name'],
            'last_name' => $passengerData['last_name'],
            'email' => $passengerData['email'],
            'phone' => $passengerData['phone'] ?? null,
            'date_of_birth' => $passengerData['date_of_birth'] ?? null,
            'nationality' => $passengerData['nationality'],
            'passport_number' => $passengerData['passport_number'],
            'id_number' => $passengerData['id_number'],
            'seat_number' => $passengerData['seat_number'] ?? null,
            'meal_preference' => $passengerData['meal_preference'] ?? 'standard',
            'status' => $passengerData['status'] ?? 'pending',
        ]);
        $ticket = $this->createTicketForPassenger($passenger);
        }

        $flight->decrement('available_seats', $validated['number_of_seats']);
        $flight->increment('booked_seats', $validated['number_of_seats']);

        $user = User::findOrFail($validated['user_id']);

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Booking created successfully');
    }

    public function show(Booking $booking)
    {
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $users = User::orderBy('first_name')->get();
        $flights = Flight::with(['origin', 'destination'])
            ->where('departure_date', '>=', now())
            ->orderBy('departure_date')
            ->get();
        return view('admin.bookings.edit', compact(['booking', 'users', 'flights']));
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $validated = $request->validated();
        $oldFlight = $booking->flight;        
        $newFlight = Flight::findOrFail($validated['flight_id']);  
        $oldSeats = $booking->number_of_seats;
        $newSeats = $validated['number_of_seats'];
        
        if ($booking->flight_id != $validated['flight_id']) {
        // CASE 1: Flight changed
        // return old seats
        $oldFlight->increment('available_seats', $oldSeats);
        $oldFlight->decrement('booked_seats', $oldSeats);

        // check new flight
        if ($newFlight->available_seats < $newSeats) {
            return back()->withErrors(['number_of_seats' => 'Not enough seats available!']);
        }
        if ($newFlight->departure_date < now()) {
            return back()->withErrors(['flight_id' => 'Cannot modify a booking for a departed flight']);
        }

        // book new seats
        $newFlight->decrement('available_seats', $newSeats);
        $newFlight->increment('booked_seats', $newSeats);

    } else {
        // CASE 2: Same flight, seats changed
        if ($newSeats > $oldSeats) {
            $difference = $newSeats - $oldSeats;

            if ($newFlight->available_seats < $difference) {
                return back()->withErrors(['number_of_seats' => 'Not enough seats available!']);
            }

            $newFlight->decrement('available_seats', $difference);
            $newFlight->increment('booked_seats', $difference);

        } elseif ($newSeats < $oldSeats) {
            $difference = $oldSeats - $newSeats;

            $newFlight->increment('available_seats', $difference);
            $newFlight->decrement('booked_seats', $difference);
        }
    }

            $booking->update($validated); 
            if (isset($validated['passengers'])) {
                $booking->passengers()->delete();
                foreach ($validated['passengers'] as $passengerData) {
                    $passenger = $booking->passengers()->create($passengerData);
                    $this->createTicketForPassenger($passenger);
                }
            }
        return redirect()->route('admin.bookings.show', $booking)
    ->with('success', 'Booking updated successfully');

    }

    public function destroy(Booking $booking)
    {
        $flight = $booking->flight;
        $flight->increment('available_seats', $booking->number_of_seats);
        $flight->decrement('booked_seats', $booking->number_of_seats);
        $booking->passengers()->delete();
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully!');
    }
    protected function createTicketForPassenger(Passenger $passenger)
    {
        $ticket = Ticket::create([
            'passenger_id' => $passenger->id,
            'ticket_number' => Ticket::generateTicketNumber(),
            'first_name' => $passenger->first_name,
            'last_name' => $passenger->last_name,
            'email' => $passenger->email,
            'phone' => $passenger->phone,
            'seat_number' => $passenger->seat_number,
            'class' => 'economy', // Default class
            'meal_preference' => $passenger->meal_preference ?? 'standard',
            'status' => 'issued',
            'issued_at' => now(),
        ]);
        return $ticket;
    }
}
