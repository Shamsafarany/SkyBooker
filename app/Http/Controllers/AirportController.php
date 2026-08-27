<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Http\Requests\Airport\StoreAirportRequest;
use App\Http\Requests\Airport\UpdateAirportRequest;
use App\Services\Admin\AirportService;
class AirportController extends Controller
{
    public function __construct(private AirportService $airportService) {}
    public function index()
    {
        $data = $this->airportService->getAllWithStats();
        return view('admin.airports.index', ['airports' => $data['airports'],
        'stats' => $data['stats']]);
    }

    public function create()
    {
        return view('admin.airports.create');
    }

    public function store(StoreAirportRequest $request)
    {
        $airport = $this->airportService->create($request->validated());
        return redirect()
            ->route('admin.airports.index')
            ->with('success', 'Airport "' . $airport->name . '" created successfully!');
    }
    
    public function edit(Airport $airport)
    {
        return view('admin.airports.edit', compact('airport'));
    }

    public function update(UpdateAirportRequest $request, Airport $airport)
    {
        $this->airportService->update($airport, $request->validated());
        return redirect()->route('admin.airports.index')
    ->with('success', 'Airport updated successfully!');
    }
    public function destroy(Airport $airport)
    {
        $this->airportService->delete($airport);
        return redirect()->route('admin.airports.index')->with('success', 'Airport deleted successfully!');
    }
}
