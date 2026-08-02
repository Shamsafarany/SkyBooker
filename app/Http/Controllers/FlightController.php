<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        //
    }

    public function show(string $id)
    {
        $flight = Flight::with([
            'airline',
            'origin',
            'destination',
            'airplane',
        ])->findOrFail($id);
        $passengers = Passenger::whereHas('booking', function ($query) use ($id) {
        $query->where('flight_id', $id);
            })->with(['ticket'])->paginate(15);
        return view('admin.flights.show', compact('flight', 'passengers'));
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
