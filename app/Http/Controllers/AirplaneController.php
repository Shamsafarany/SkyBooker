<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airplane;

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
