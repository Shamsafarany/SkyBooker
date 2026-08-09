<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airplane;
use Illuminate\Validation\Rule;

class AirplaneController extends Controller
{
    public function index()
    {
        
        $airplanes = Airplane::withCount('flights')
            ->orderBy('manufacturer')
            ->orderBy('model')
            ->get();
        $stats = [
            'total' => $airplanes->count(),
            'active' => $airplanes->where('status', 'active')->count(),
            'inactive' => $airplanes->where('status', 'inactive')->count(),
            'maintenance' => $airplanes->where('status', 'maintenance')->count(),
            'total_capacity' => $airplanes->sum('capacity'),
            'total_flights' => $airplanes->sum('flights_count'),
        ];
        return view('admin.airplanes.index', compact('airplanes','stats'));
    }

    public function create()
    {
        return view('admin.airplanes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'registration' => 'required|string|max:255|unique:airplanes,registration',
            'capacity' => 'required|integer|min:1|max:1000',
            'year' => 'required|integer|min:1950|max:' . date('Y'),
            'status' => ['required', Rule::in(['active', 'inactive', 'maintenance', 'retired'])],
        ]);
        $airplane = Airplane::create($validated);
        return redirect()
            ->route('admin.airplanes.index')
            ->with('success', 'Airplane "' . $airplane->model . '" created successfully!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Airplane $airplane)
    {
        return view('admin.airplanes.edit', compact('airplane'));
    }

    public function update(Request $request, Airplane $airplane)
    {
        $validated = $request->validate([
            'model' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'registration' => [
            'required',
            'string',
            'max:255',
            Rule::unique('airplanes', 'registration')->ignore($airplane->id), 
            ],
            'capacity' => 'required|integer|min:1|max:1000',
            'year' => 'required|integer|min:1950|max:' . date('Y'),
            'status' => ['required', Rule::in(['active', 'inactive', 'maintenance', 'retired'])],
        ]);
        $airplane->update($validated);
        return redirect()->route('admin.airplanes.index')->with('success', 'Airplane updated successfully!');
    }
    public function destroy(Airplane $airplane)
    {
        $airplane->delete();
        return redirect()->route('admin.airplanes.index')->with('success', 'Airplane deleted successfully!');
    }
}
