<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airport;

class AirportController extends Controller
{
    public function index()
    {
        $airports = Airport::withCount(['departingFlights', 'arrivingFlights'])
            ->orderBy('name')
            ->get();

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
