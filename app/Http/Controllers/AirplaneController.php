<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airplane;
use Illuminate\Validation\Rule;
use App\Http\Requests\Airplane\StoreAirplaneRequest;
use App\Http\Requests\Airplane\UpdateAirplaneRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
class AirplaneController extends Controller
{
    public function index()
    {
        
        $airplanes = Airplane::withCount('flights')
            ->orderBy('manufacturer')
            ->orderBy('model')
            ->get();
        
        
        $stats = Cache::remember('admin.airplanes.stats', 60, function() use ($airplanes){
            return [
            'total' => $airplanes->count(),
            'active' => $airplanes->where('status', 'active')->count(),
            'inactive' => $airplanes->where('status', 'inactive')->count(),
            'maintenance' => $airplanes->where('status', 'maintenance')->count(),
            'total_capacity' => $airplanes->sum('capacity'),
            'total_flights' => $airplanes->sum('flights_count'),
            ];
        });
        Log::channel('booking')->info('Stats are cached.');

        return view('admin.airplanes.index', compact('airplanes','stats'));
    }

    public function create()
    {
        return view('admin.airplanes.create');
    }

    public function store(StoreAirplaneRequest $request)
    {
        $validated = $request->validated();
        $airplane = Airplane::create($validated);
        Cache::forget('admin.airplanes.stats');   
        Log::channel('booking')->info('Stats are cleared.');
        return redirect()
            ->route('admin.airplanes.index')
            ->with('success', 'Airplane "' . $airplane->model . '" created successfully!');
    }

    public function edit(Airplane $airplane)
    {
        return view('admin.airplanes.edit', compact('airplane'));
    }

    public function update(UpdateAirplaneRequest $request, Airplane $airplane)
    {
        $validated = $request->validated();
        $airplane->update($validated);
        Cache::forget('admin.airplanes.stats');
        Log::channel('booking')->info('Stats are cleared.');
        return redirect()->route('admin.airplanes.index')->with('success', 'Airplane updated successfully!');
    }
    public function destroy(Airplane $airplane)
    {
        $airplane->delete();
        Cache::forget('admin.airplanes.stats');
        Log::channel('booking')->info('Stats are cleared.');
        return redirect()->route('admin.airplanes.index')->with('success', 'Airplane deleted successfully!');
    }
}
