<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Airport\AirportChangeStatusRequest;
use App\Http\Resources\Api\V1\AirportResource;
use App\Models\Airport;
use Illuminate\Http\Request;
use App\Http\Requests\Airport\StoreAirportRequest;
use App\Http\Requests\Airport\UpdateAirportRequest;
use App\Http\Resources\Api\V1\FlightCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Services\Admin\AirportService;

class AirportController extends Controller
{
    public function __construct(private AirportService $airportService) {}
    public function index(Request $request)
    {
        try{
            $airports = $this->airportService->getApiList($request);

            Log::info('API AIRPORT LIST: Cache HIT');

            return Response::success($airports, 'Airports retrieved');

        } catch (\Throwable $e) {
            Log::error('AIRPORT INDEX ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve airports', 500);
        }
    }

    public function store(StoreAirportRequest $request)
    {
        try {
            $airport = $this->airportService->create($request->validated());
            $airport->load(['departingFlights', 'arrivingFlights']);

            return Response::success(new AirportResource($airport), 'Airport created', 201);

        } catch (\Throwable $e) {
            Log::error('AIRPORT STORE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to create airport', 500);
        }
    }

    public function show(Airport $airport)
    {
        try {
            $airportData = $this->airportService->getApiShow($airport);

            Log::info("API AIRPORT SHOW: Cache HIT for ID {$airport->id}");

            return Response::success($airportData, 'Airport retrieved');

        } catch (\Throwable $e) {
            Log::error('AIRPORT SHOW ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve airport', 500);
        }
    }

    public function update(UpdateAirportRequest $request, Airport $airport)
    {
        try {
            $airport= $this->airportService->update($airport, $request->validated());
            $airport->load(['departingFlights', 'arrivingFlights']);

            return Response::success(new AirportResource($airport), 'Airport updated');

        } catch (\Throwable $e) {
            Log::error('AIRPORT UPDATE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to update airport', 500);
        }
    }

    public function destroy(Airport $airport)
    {
        try {
            $this->airportService->delete($airport);
            return Response::success(null, 'Airport deleted', 204);

        } catch (\Throwable $e) {
            Log::error('AIRPORT DELETE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to delete airport', 500);
        }
    }

    /**
     * Search airports by code, name, or city
     */
    public function search(Request $request)
    {
        $query = Airport::query();
        
        if ($request->has('code')) {
            $query->where('code', 'LIKE', '%' . $request->code . '%');
        }
        
        if ($request->has('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }
        
        if ($request->has('city')) {
            $query->where('city', 'LIKE', '%' . $request->city . '%');
        }
        
        $airports = $query->get();
        
        return AirportResource::collection($airports);
    }
    public function flights(string $code)
    {
        try {
            $result = $this->airportService->getFlightsForAirport($code);
            return Response::success(new FlightCollection($result['flights']), 'Flights retrieved');

        } catch (\Throwable $e) {
            Log::error('AIRPORT FLIGHTS ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve flights for airport', 500);
        }
    }

    public function changeStatus(AirportChangeStatusRequest $request, Airport $airport)
    {
        $updatedAirport = app(AirportService::class)
            ->changeStatus($airport, $request->validated()['status']);
        if (!$updatedAirport) {
        return Response::error('Invalid Status Transition.', 500);
        }

        return response()->json([
            'message' => 'Airport status updated successfully.',
            'airport' => $updatedAirport
        ]);
    }
}