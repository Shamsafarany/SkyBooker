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
        $validated = $request->validated();
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

        $flight->decrement('available_seats', $validated['number_of_seats']);
        $flight->increment('booked_seats', $validated['number_of_seats']);

        Log::channel('booking')->info('Booking completed successfully', [
            'booking_id' => $booking->id,
            'booking_reference' => $booking->booking_reference,
            'total_price' => $booking->total_price,
            'status' => $booking->status,
            'customer_id' => $validated['user_id'],
            'flight_id' => $flight->id,
        ]);

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Booking created successfully! You can now add passengers.');

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

    public function archive()
    {
        $bookings = Booking::onlyTrashed()
            ->with(['user', 'flight'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        $stats = [
            'total_deleted' => Booking::onlyTrashed()->count(),
            'today_deleted' => Booking::onlyTrashed()
                ->whereDate('deleted_at', today())
                ->count(),
            'this_week_deleted' => Booking::onlyTrashed()
                ->whereDate('deleted_at', '>=', now()->startOfWeek())
                ->count(),
            'this_month_deleted' => Booking::onlyTrashed()
                ->whereDate('deleted_at', '>=', now()->startOfMonth())
                ->count(),
        ];

        return view('admin.bookings.archive', compact('bookings', 'stats'));
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
    try {
        $oldData = [
            'user_id' => $booking->user_id,
            'flight_id' => $booking->flight_id,
            'number_of_seats' => $booking->number_of_seats,
            'total_price' => $booking->total_price,
            'status' => $booking->status,
            'booking_date' => $booking->booking_date,
        ];

        $flightChanged = $booking->flight_id != $request->flight_id;
        $oldFlight = $booking->flight;
        $newFlight = Flight::findOrFail($request->flight_id);

        if ($flightChanged) {
            // Return seats to old flight
            $oldFlight->increment('available_seats', $booking->number_of_seats);
            $oldFlight->decrement('booked_seats', $booking->number_of_seats);

            // Check new flight availability
            if ($newFlight->available_seats < $request->number_of_seats) {
                Log::channel('booking')->warning('Insufficient seats on new flight', [
                    'flight_id' => $newFlight->id,
                    'requested' => $request->number_of_seats,
                    'available' => $newFlight->available_seats,
                ]);

                // Return seats to old flight (undo)
                $oldFlight->decrement('available_seats', $booking->number_of_seats);
                $oldFlight->increment('booked_seats', $booking->number_of_seats);

                return back()
                    ->with('error', 'Not enough seats on new flight! Only ' . $newFlight->available_seats . ' seats available.')
                    ->withInput();
            }

            // Book new flight
            $newFlight->decrement('available_seats', $request->number_of_seats);
            $newFlight->increment('booked_seats', $request->number_of_seats);
        }

        // Handle seat count change (same flight)
        if ($booking->number_of_seats != $request->number_of_seats) {
            $seatDifference = $request->number_of_seats - $booking->number_of_seats;
            if ($seatDifference > 0) {
                // More seats - check availability
                if ($newFlight->available_seats < $seatDifference) {
                    Log::channel('booking')->warning('Insufficient seats for seat increase', [
                        'flight_id' => $newFlight->id,
                        'requested_additional' => $seatDifference,
                        'available' => $newFlight->available_seats,
                    ]);

                    return back()
                        ->with('error', 'Not enough additional seats! Only ' . $newFlight->available_seats . ' more seats available.')
                        ->withInput();
                }

                $newFlight->decrement('available_seats', $seatDifference);
                $newFlight->increment('booked_seats', $seatDifference);

            } else {
                // Less seats - return them
                $seatsToReturn = abs($seatDifference);
                $newFlight->increment('available_seats', $seatsToReturn);
                $newFlight->decrement('booked_seats', $seatsToReturn);

                // Check if passengers exceed new seat count
                $passengerCount = $booking->passengers()->count();
                if ($passengerCount > $request->number_of_seats) {
                    return back()
                        ->with('error', 'Cannot reduce seats below passenger count (' . $passengerCount . ' passengers).')
                        ->withInput();
                }
            }
        }

        $booking->update($request->validated());

        if ($booking->wasChanged('status')) {
            $oldStatus = $booking->getOriginal('status');
            $newStatus = $booking->status;

            Log::channel('booking')->info('Booking status changed', [
                'booking_id' => $booking->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);

            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                $flight = $booking->flight;
                $flight->increment('available_seats', $booking->number_of_seats);
                $flight->decrement('booked_seats', $booking->number_of_seats);

                Log::channel('booking')->warning('Booking cancelled - seats returned', [
                    'booking_id' => $booking->id,
                    'flight_id' => $flight->id,
                    'seats_returned' => $booking->number_of_seats,
                ]);
            }
        }

        // ✅ Log success
        Log::channel('booking')->info('Booking updated successfully', [
            'booking_id' => $booking->id,
            'booking_reference' => $booking->booking_reference,
            'updated_fields' => $booking->getChanges(),
        ]);

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Booking updated successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::channel('booking')->warning('Booking update validation failed', [
            'errors' => $e->errors(),
            'booking_id' => $booking->id,
        ]);
        throw $e;

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::channel('booking')->error('Model not found in booking update', [
            'error' => $e->getMessage(),
            'model' => $e->getModel(),
            'booking_id' => $booking->id,
        ]);

        return back()
            ->with('error', 'Flight or user not found.')
            ->withInput();

    } catch (\Exception $e) {
        Log::channel('booking')->error('Booking update failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'booking_id' => $booking->id,
            'booking_reference' => $booking->booking_reference,
            'data' => $request->validated(),
            'ip' => $request->ip(),
        ]);

        return back()
            ->with('error', 'Failed to update booking. Please try again.')
            ->withInput();
    }
}

    public function destroy($id)
    {
        try {
        $booking= Booking::withTrashed()->findOrFail($id);
        $bookingId = $booking->id;
        $bookingReference = $booking->booking_reference;
        foreach ($booking->passengers as $passenger) {
            if ($passenger->ticket) {
                $passenger->ticket->delete(); 
            }
            $passenger->delete(); 
        }
        if($booking->trashed()){
            $booking->forceDelete();
            return redirect()->route('admin.bookings.index') ->with('success', 'Booking deleted successfully!');
        }
        $booking->delete();
        Log::channel('booking')->warning('Booking soft deleted', [
            'booking_id' => $bookingId,
            'booking_reference' => $bookingReference,
            'ip' => request()->ip(),
        ]);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully!');

        } catch (\Exception $e) {
            Log::channel('booking')->error('Failed to delete booking', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to delete booking.');
        }
    }

    public function restore(Booking $booking, Request $request){
        $booking = Booking::withTrashed()->findOrFail($booking->id);
        $booking->restore();
        foreach ($booking->passengers()->withTrashed()->get() as $passenger) {
            if ($passenger->trashed()) {
                $passenger->restore();
            }
            if ($passenger->ticket && $passenger->ticket->trashed()) {
                $passenger->ticket->restore();
            }
        }
        return redirect()->route('admin.bookings.index')->with('success', 'Booking restored successfully!');
    }
}
