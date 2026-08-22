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
use Illuminate\Support\Facades\Log;


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
    try {
        Log::channel('booking')->info('Booking creation started', [
            'flight_id' => $request->flight_id,
            'number_of_seats' => $request->number_of_seats,
            'total_price' => $request->total_price,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $validated = $request->validated();
        if (!isset($validated['passengers']) || empty($validated['passengers'])) {
            Log::channel('booking')->warning('Booking attempted with no passengers', [
                'flight_id' => $validated['flight_id'],
            ]);
            return back()
                ->with('error', 'At least one passenger is required.')
                ->withInput();
        }

        $flight = Flight::findOrFail($validated['flight_id']);
        
        if ($flight->available_seats < $validated['number_of_seats']) {
            Log::channel('booking')->warning('Insufficient seats for booking', [
                'flight_id' => $flight->id,
                'flight_number' => $flight->flight_number,
                'requested' => $validated['number_of_seats'],
                'available' => $flight->available_seats,
            ]);
            return back()
                ->with('error', 'Not enough seats available! Only ' . $flight->available_seats . ' seats left.')
                ->withInput();
        }
        $booking = Booking::create($validated);

        Log::channel('booking')->info('Booking record created', [
            'booking_id' => $booking->id,
            'booking_reference' => $booking->booking_reference,
        ]);

        $passengerCount = 0;
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

            Log::channel('booking')->info('Passenger added to booking', [
                'booking_id' => $booking->id,
                'passenger_id' => $passenger->id,
                'passenger_name' => $passenger->first_name . ' ' . $passenger->last_name,
                'seat_number' => $passenger->seat_number,
            ]);

            $ticket = $this->createTicketForPassenger($passenger);
            $passengerCount++;

            Log::channel('booking')->info('Ticket generated for passenger', [
                'booking_id' => $booking->id,
                'passenger_id' => $passenger->id,
                'ticket_number' => $ticket->ticket_number,
            ]);
        }

        $flight->decrement('available_seats', $validated['number_of_seats']);
        $flight->increment('booked_seats', $validated['number_of_seats']);

        Log::channel('booking')->info('Flight seats updated', [
            'flight_id' => $flight->id,
            'flight_number' => $flight->flight_number,
            'seats_booked' => $validated['number_of_seats'],
            'available_seats' => $flight->available_seats,
            'booked_seats' => $flight->booked_seats,
        ]);

        $user = User::findOrFail($validated['user_id']);

        // ✅ Log success
        Log::channel('booking')->info('Booking completed successfully', [
            'booking_id' => $booking->id,
            'booking_reference' => $booking->booking_reference,
            'total_passengers' => $passengerCount,
            'total_price' => $booking->total_price,
            'status' => $booking->status,
            'customer_id' => $validated['user_id'],
            'flight_id' => $flight->id,
        ]);

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Booking created successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::channel('booking')->warning('Booking validation failed', [
            'errors' => $e->errors(),
            'data' => $request->all(),
            'ip' => $request->ip(),
        ]);
        throw $e;

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::channel('booking')->error('Model not found in booking creation', [
            'error' => $e->getMessage(),
            'model' => $e->getModel(),
            'ip' => $request->ip(),
        ]);

        return back()
            ->with('error', 'Flight or user not found.')
            ->withInput();

    } catch (\Exception $e) {
        Log::channel('booking')->error('Booking creation failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'data' => $request->validated(),
            'ip' => $request->ip(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return back()
            ->with('error', 'Failed to create booking. Please try again.')
            ->withInput();
    }
}

    public function show(Booking $booking)
    {
        $booking->load(['user', 'flight', 'passengers.ticket']);
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
        foreach ($booking->passengers as $passenger) {
            if ($passenger->ticket) {
                $passenger->ticket->delete();
            }
        }
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
