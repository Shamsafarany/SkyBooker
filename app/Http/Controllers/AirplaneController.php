<?php

namespace App\Http\Controllers;

use App\Models\Airplane;
use App\Http\Requests\Airplane\StoreAirplaneRequest;
use App\Http\Requests\Airplane\UpdateAirplaneRequest;
use App\Services\Admin\AirplaneService;
class AirplaneController extends Controller
{
    public function __construct(private AirplaneService $airplaneService) {}
    public function index()
    {
        $data = $this->airplaneService->getAllWithStats();
        return view('admin.airplanes.index', ['airplanes' => $data['airplanes'],
        'stats' => $data['stats']]);
    }

    public function create()
    {
        return view('admin.airplanes.create');
    }

    public function store(StoreAirplaneRequest $request)
    {
        $airplane = $this->airplaneService->create($request->validated());
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
        $this->airplaneService->update($airplane, $request->validated());
        return redirect()->route('admin.airplanes.index')->with('success', 'Airplane updated successfully!');
    }
    public function destroy(Airplane $airplane)
    {
        $this->airplaneService->delete($airplane);
        return redirect()->route('admin.airplanes.index')->with('success', 'Airplane deleted successfully!');
    }
}
