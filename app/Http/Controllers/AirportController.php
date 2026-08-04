<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Airport;

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

    public function store(Request $request) 
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|size:3|unique:airports,code',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'terminals' => 'required|integer|min:1',
            'status' => ['required', Rule::in(['active', 'inactive', 'maintenance', 'closed'])],
        ]);

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

    public function update(Request $request, Airport $airport)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
            'required',
            'string',
            'size:3',
            Rule::unique('airports', 'code')->ignore($airport->id), 
            ],
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'terminals' => 'required|integer|min:1',
            'status' => ['required', Rule::in(['active', 'inactive', 'maintenance', 'closed'])],
        ]);
        $airport->update($validated);
        return redirect()->route('admin.airports.index')
    ->with('success', 'Airport updated successfully!');
    }
    public function destroy(string $id)
    {
        //
    }
}
