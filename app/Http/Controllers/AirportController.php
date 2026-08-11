<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Airport;
use App\Http\Requests\Airport\StoreAirportRequest;
use App\Http\Requests\Airport\UpdateAirportRequest;

class AirportController extends Controller
{
    public function index()
    {
        $airports = Airport::withCount(['departingFlights', 'arrivingFlights'])
            ->latest()->get();

        $stats = [
            'total' => $airports->count(),
            'active' => $airports->where('status', 'active')->count(),
            'inactive' => $airports->where('status', 'inactive')->count(),
            'maintenance' => $airports->where('status', 'maintenance')->count(),
            'closed' => $airports->where('status', 'closed')->count(),
            'total_terminals' => $airports->sum('terminals'),
            'total_departing' => $airports->sum('departing_flights_count'),
            'total_arriving' => $airports->sum('arriving_flights_count'),
            'total_flights' => $airports->sum('departing_flights_count') + $airports->sum('arriving_flights_count'),
            'by_country' => $airports->groupBy('country')->map->count()->sortDesc()->take(5),
        ];

        return view('admin.airports.index', compact('airports', 'stats'));
    }

    public function create()
    {
        return view('admin.airports.create');
    }

    public function store(StoreAirportRequest $request) 
    {
        $validated = $request->validated();

        $airport = Airport::create($validated);
        
        return redirect()
            ->route('admin.airports.index')
            ->with('success', 'Airport "' . $airport->name . '" created successfully!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Airport $airport)
    {
        return view('admin.airports.edit', compact('airport'));
    }

    public function update(UpdateAirportRequest $request, Airport $airport)
    {
        $validated = $request->validated();
        $airport->update($validated);
        return redirect()->route('admin.airports.index')
    ->with('success', 'Airport updated successfully!');
    }
    public function destroy(Airport $airport)
    {
        $airport->delete();
        return redirect()->route('admin.airports.index')->with('success', 'Airport deleted successfully!');
    }
}
