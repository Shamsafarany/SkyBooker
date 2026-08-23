<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Flight;
use App\Models\Passenger;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Airplane;
use App\Http\Requests\Flight\StoreFlightRequest;
use App\Http\Requests\Flight\UpdateFlightRequest;
use Illuminate\Support\Facades\Log;
use App\Services\WeatherService;
class FlightController extends Controller
{
    

    private function getFlights(){
        $flights = Flight::with([
            'airline',
            'origin',
            'destination',
            'airplane',
        ])->orderBy('departure_date')->get();
        return $flights;
    }
    public function index(Request $request){
        
        $flights = $this->getFlights();
        $airlines = Airline::with(['flights' => function ($query) {
            $query->with([
                'origin',
                'destination',
                'airplane',
            ])->orderBy('departure_date');
        }])->has('flights')->get();
        $stats = [
            'total' => $flights->count(),
            'total_airlines' => $airlines->count(),
            'open' => $flights->where('status', 'open')->count(),
            'closing' => $flights->where('status', 'closing')->count(),
            'completed' => $flights->where('status', 'completed')->count(),
            'revenue' => $flights->sum('price'),
        ];
        return view('admin.flights.index', compact('flights', 'airlines', 'stats'));

    }

    public function create()
    {
        $airlines = Airline::orderBy('name')->get();
        $airports = Airport::orderBy('name')->get();
        $airplanes = Airplane::where('status', 'active')->orderBy('model')->get();

        return view('admin.flights.create', compact('airlines', 'airports', 'airplanes'));
    }

    public function store(StoreFlightRequest $request)
    {
        try {
            $validated = $request->validated();

            Log::channel('booking')->info('Flight creation started', [
                'flight_number' => $validated['flight_number'],
                'airline_id' => $validated['airline_id'],
                'origin_airport_id' => $validated['origin_airport_id'],
                'destination_airport_id' => $validated['destination_airport_id'],
                'departure_date' => $validated['departure_date'],
                'arrival_date' => $validated['arrival_date'],
                'total_seats' => $validated['total_seats'],
                'price' => $validated['price'],
                'status' => $validated['status'],
                'ip' => $request->ip(),
            ]);

            $flight = Flight::create($validated);

            Log::channel('booking')->info('Flight created successfully', [
                'flight_id' => $flight->id,
                'flight_number' => $flight->flight_number,
                'airline_id' => $flight->airline_id,
                'origin_airport_id' => $flight->origin_airport_id,
                'destination_airport_id' => $flight->destination_airport_id,
                'departure_date' => $flight->departure_date,
                'arrival_date' => $flight->arrival_date,
                'total_seats' => $flight->total_seats,
                'available_seats' => $flight->available_seats,
                'booked_seats' => $flight->booked_seats,
                'price' => $flight->price,
                'status' => $flight->status,
            ]);

            return redirect()->route('admin.flights.show', $flight)
                ->with('success', 'Flight created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('booking')->warning('Flight validation failed', [
                'errors' => $e->errors(),
                'data' => $request->all(),
            ]);
            throw $e;

        } catch (\Exception $e) {
            Log::channel('booking')->error('Flight creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->validated(),
                'ip' => $request->ip(),
            ]);

            return back()
                ->with('error', 'Failed to create flight. Please try again.')
                ->withInput();
        }
    }
    public function show(string $id)
    {
        $flight = Flight::with([
            'airline',
            'origin',
            'destination',
            'airplane',
            'bookings.passengers.ticket'
        ])->findOrFail($id);
        $passengers = Passenger::whereHas('booking', function ($query) use ($id) {
        $query->where('flight_id', $id);
            })->with(['ticket'])->paginate(15);
        return view('admin.flights.show', compact('flight', 'passengers'));
    }

    public function edit(Flight $flight)
    {
        $airlines = Airline::orderBy('name')->get();
        $airports = Airport::orderBy('name')->get();
        $airplanes = Airplane::where('status', 'active')->orderBy('model')->get();
        return view('admin.flights.edit', compact('flight', 'airlines', 'airports', 'airplanes'));
    }

    public function update(UpdateFlightRequest $request, Flight $flight)
    {
        try {
            $oldData = [
                'flight_number' => $flight->flight_number,
                'status' => $flight->status,
                'price' => $flight->price,
                'departure_date' => $flight->departure_date,
                'arrival_date' => $flight->arrival_date,
                'total_seats' => $flight->total_seats,
            ];

            $flight->update($request->validated());

            if ($flight->wasChanged('status')) {
                Log::channel('booking')->info('Flight status changed', [
                    'flight_id' => $flight->id,
                    'flight_number' => $flight->flight_number,
                    'old_status' => $flight->getOriginal('status'),
                    'new_status' => $flight->status,
                ]);
            }

            if ($flight->wasChanged('price')) {
                Log::channel('booking')->info('Flight price changed', [
                    'flight_id' => $flight->id,
                    'flight_number' => $flight->flight_number,
                    'old_price' => $flight->getOriginal('price'),
                    'new_price' => $flight->price,
                ]);
            }

            Log::channel('booking')->info('Flight updated successfully', [
                'flight_id' => $flight->id,
                'flight_number' => $flight->flight_number,
                'updated_fields' => $flight->getChanges(),
            ]);

            return redirect()->route('admin.flights.show', $flight)
                ->with('success', 'Flight updated successfully!');

        } catch (\Exception $e) {
            Log::channel('booking')->error('Flight update failed', [
                'flight_id' => $flight->id,
                'flight_number' => $flight->flight_number,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->with('error', 'Failed to update flight. Please try again.')
                ->withInput();
        }
    }

    public function destroy(Flight $flight)
    {
        try {
            $flightNumber = $flight->flight_number;
            $flightId = $flight->id;

            Log::channel('booking')->warning('Flight deletion initiated', [
                'flight_id' => $flightId,
                'flight_number' => $flightNumber,
                'status' => $flight->status,
                'total_seats' => $flight->total_seats,
                'booked_seats' => $flight->booked_seats,
                'has_bookings' => $flight->bookings()->exists(),
                'ip' => request()->ip(),
            ]);

            if ($flight->bookings()->exists()) {
                Log::channel('booking')->warning('Flight deletion blocked - has bookings', [
                    'flight_id' => $flightId,
                    'flight_number' => $flightNumber,
                    'bookings_count' => $flight->bookings()->count(),
                ]);
                return back()->with('error', 'Cannot delete flight with existing bookings.');
            }

            $flight->delete();

            Log::channel('booking')->warning('Flight deleted successfully', [
                'flight_id' => $flightId,
                'flight_number' => $flightNumber,
            ]);
            return redirect()->route('admin.flights.index')
                ->with('success', 'Flight deleted successfully!');

        } catch (\Exception $e) {
            Log::channel('booking')->error('Flight deletion failed', [
                'flight_id' => $flight->id,
                'flight_number' => $flight->flight_number,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->with('error', 'Failed to delete flight. Please try again.');
        }
    }

    public function weather(Flight $flight, WeatherService $weather)
    {
        $data = $weather->getWeatherByCity($flight->origin->city);
        return response()->success($data, 'Weather for flight city');
    }

    
}



















