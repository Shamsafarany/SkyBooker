<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Flight;
use App\Models\Passenger;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Airplane;
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
    public function index(){
        
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'flight_number' => 'required|string|max:255|unique:flights,flight_number',
            'airline_id' => 'required|exists:airlines,id',
            'origin_airport_id' => 'required|exists:airports,id|different:destination_airport_id',
            'destination_airport_id' => 'required|exists:airports,id|different:origin_airport_id',
            'airplane_id' => 'required|exists:airplanes,id',
            'departure_date' => 'required|date|after_or_equal:today',
            'departure_time' => 'required',
            'arrival_date' => 'required|date|after_or_equal:departure_date',
            'arrival_time' => 'required',
            'duration' => 'required|string|max:50',
            'price' => 'required|numeric|min:0|max:99999.99',
            'total_seats' => 'required|integer|min:1|max:1000',
            'status' => ['required', Rule::in(['scheduled', 'open', 'closing', 'completed', 'cancelled', 'delayed', 'boarding', 'departed'])],
            'booking_deadline' => 'nullable|date|before:departure_date',
        ]);

        $flight = Flight::create($validated);
        return redirect()->route('admin.flights.show', $flight)->with('success', 'Flight created successfully');
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

    public function update(Request $request, Flight $flight)
    {
        $validated = $request->validate([
            'flight_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('flights', 'flight_number')->ignore($flight->id),
            ],
            'airline_id' => 'required|exists:airlines,id',
            'origin_airport_id' => 'required|exists:airports,id|different:destination_airport_id',
            'destination_airport_id' => 'required|exists:airports,id|different:origin_airport_id',
            'airplane_id' => 'required|exists:airplanes,id',
            'departure_date' => 'required|date',
            'departure_time' => 'required',
            'arrival_date' => 'required|date|after_or_equal:departure_date',
            'arrival_time' => 'required',
            'duration' => 'required|string|max:50',
            'price' => 'required|numeric|min:0|max:99999.99',
            'total_seats' => 'required|integer|min:1|max:1000',
            'status' => ['required', Rule::in(['scheduled', 'open', 'closing', 'completed', 'cancelled', 'delayed', 'boarding', 'departed'])],
            'booking_deadline' => 'nullable|date|before:departure_date',
        ]);
        $flight->update($validated);
        return redirect()
            ->route('admin.flights.show', $flight)
            ->with('success', 'Flight "' . $flight->flight_number . '" updated successfully!');
    }
    public function destroy(Flight $flight)
    {
        $flight->delete();
        return redirect()->route('admin.flights.index')->with('success', 'Flight deleted successfully!');
    }
}
