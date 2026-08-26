<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Passenger;
use App\Models\Booking;
use App\Models\Ticket;
use App\Http\Requests\Passenger\StorePassengerRequest;
use App\Http\Requests\Passenger\UpdatePassengerRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PassengerController extends Controller
{
    public function index()
    {
        $passengers = Passenger::all();
        $stats = Cache::remember('admin.passengers.stats', 60, function() use ($passengers) {
            return [
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
        });
        Log::channel('booking')->info('Stats are cached.');
        

        return view('admin.passengers.index', compact('passengers', 'stats'));
    }

    public function create(Request $request)
    {
        $bookingId = $request->query('booking_id');
        $booking = Booking::with(['flight', 'user'])->findOrFail($bookingId);
        return view('admin.passengers.create', compact('booking'));
    }
    public function store(StorePassengerRequest $request)
    {
        try{
            $validated = $request->validated();
            $passenger = Passenger::create($validated);
            $booking = $passenger->booking;
            $booking->increment('number_of_seats', 1);
            $flight = $booking->flight;
            $newTotal = $booking->passengers()->count() * $flight->price;
            $booking->update(['total_price' => $newTotal]);
            $flight->decrement('available_seats');
            $flight->increment('booked_seats');
            $ticket = $this->createTicketForPassenger($passenger, $validated['seat_number'] ?? null);
            Cache::forget('admin.passengers.stats');
            Log::channel('booking')->info('Stats are cleared.');

            Log::channel('booking')->info('Passenger added successfully', [
            'passenger_id' => $passenger->id,
            'booking_id' => $booking->id,
            ]);

            return redirect()->route('admin.bookings.show', $booking->id)
                ->with('success', 'Passenger added successfully!'); 
        } catch (\Illuminate\Validation\ValidationException $e) {  
            Log::channel('booking')->warning('Passenger validation failed', [
                'errors' => $e->errors(),
                'data' => $request->all(),
            ]);
            throw $e;

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::channel('booking')->error('Model not found in passenger creation', [
            'error' => $e->getMessage(),
            'model' => $e->getModel(),
        ]);
        return back()
            ->with('error', 'Booking or flight not found.')
            ->withInput();

        }catch (\Exception $e) {
        Log::channel('booking')->error('Failed to add passenger', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'data' => $request->validated(),
            'ip' => $request->ip(),
        ]);
        return back()
            ->with('error', 'Failed to add passenger. Please try again.')
            ->withInput();
    }
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
        Cache::forget('admin.passengers.stats');
        Log::channel('booking')->info('Stats are cleared.');
        if ($passenger->ticket) {
            $passenger->ticket->update([
                'first_name' => $validated['first_name'] ?? $passenger->first_name,
                'last_name' => $validated['last_name'] ?? $passenger->last_name,
                'email' => $validated['email'] ?? $passenger->email,
                'phone' => $validated['phone'] ?? $passenger->phone,
                'seat_number' => $validated['seat_number'] ?? $passenger->ticket->seat_number,
                'meal_preference' => $validated['meal_preference'] ?? $passenger->ticket->meal_preference,
        ]);
        Log::channel('booking')->info('Passenger updated successfully', [
            'passenger_id' => $passenger->id]);
        }
        return redirect()->route('admin.bookings.show', $passenger->booking_id)->with('success', 'Passenger "' . $passenger->id . '" updated successfully!');
    }
    public function destroy(Passenger $passenger)
    {
        $booking = $passenger->booking;
        $flight = $booking->flight;
        Cache::forget('admin.passengers.stats');
        Log::channel('booking')->info('Stats are cleared.');
        if ($passenger->ticket) {
        $passenger->ticket->delete();
        }
        $booking->decrement('number_of_seats', 1);
        $passenger->delete();
        $booking->update([
        'total_price' => $booking->passengers()->count() * $flight->price
        ]);
        $flight->increment('available_seats');
        $flight->decrement('booked_seats');
        if ($booking->passengers()->count() === 0) {
            $booking->update([
                'status' => 'cancelled',
                'number_of_seats' => 0,
                'total_price' => 0,
            ]);
        }
        Log::channel('booking')->warning('Passenger deleted successfully', [
            'passenger_id' => $passenger->id,
            'passenger_name' => $passenger->first_name,
            'booking_id' => $passenger->booking_id,
        ]);
        return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Passenger removed successfully!');
    }
    protected function createTicketForPassenger(Passenger $passenger, $seatNumber = null)
    {
        try {
            $ticket = Ticket::create([
                'passenger_id' => $passenger->id,
                'ticket_number' => Ticket::generateTicketNumber(),
                'first_name' => $passenger->first_name,
                'last_name' => $passenger->last_name,
                'email' => $passenger->email,
                'phone' => $passenger->phone,
                'seat_number' => $seatNumber,
                'class' => 'economy',
                'meal_preference' => $passenger->meal_preference ?? 'standard',
                'status' => 'issued',
                'issued_at' => now(),
            ]);

            Log::channel('booking')->info('Ticket created', [
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'passenger_id' => $passenger->id,
            ]);
            return $ticket;
        } catch (\Exception $e) {
            Log::channel('booking')->error('Failed to create ticket', [
                'passenger_id' => $passenger->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
