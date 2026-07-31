<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flight;
use App\Models\Passenger;
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
        return view('admin.flights.index', compact('flights'));

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
        $flight = Flight::with([
            'airline',
            'origin',
            'destination',
            'airplane',
        ])->findOrFail($id);
        $passengers = Passenger::whereHas('booking', function ($query) use ($id) {
        $query->where('flight_id', $id);
            })->with(['ticket'])->get();
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
