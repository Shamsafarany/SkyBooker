<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AirportResource;
use App\Models\Airport;
use App\Models\Flight;
use Illuminate\Http\Request;
use App\Http\Requests\Airport\StoreAirportRequest;
use App\Http\Requests\Airport\UpdateAirportRequest;
use App\Http\Resources\Api\V1\FlightCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class AirportController extends Controller
{
    public function index()
    {
        try{
            $airports = Cache::remember('api.airports.list', 60, function () {
            Log::info('AIRPORT INDEX: Cache MISS - querying database');

            $airports = Airport::withCount([
                'departingFlights',
                'arrivingFlights'
            ])->get();
            return AirportResource::collection($airports)->resolve();
            });
            Log::info('AIRPORT INDEX: Cache HIT - getting cache');
            return Response::success($airports, 'Airports retrieved');
        } catch (\Throwable $e) {
            Log::error('AIRPORT INDEX ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve airports', 500);
        }
    }

    public function store(StoreAirportRequest $request)
    {
        try {
            $airport = Airport::create($request->validated());
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
            $airportData = Cache::remember("api.airports.show.{$airport->id}", 60, function () use ($airport) {
                Log::info('AIRPORT SHOW: Cache MISS - querying database');

                $airport = Airport::withCount(['departingFlights', 'arrivingFlights'])
                    ->findOrFail($airport->id);

                return (new AirportResource($airport))->resolve();
            });

            Log::info('AIRPORT SHOW: Cache HIT - getting cache');

            return Response::success($airportData, 'Airport retrieved');

        } catch (\Throwable $e) {
            Log::error('AIRPORT SHOW ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve airport', 500);
        }
    }

    public function update(UpdateAirportRequest $request, Airport $airport)
    {
        try {
            $airport->update($request->validated());
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
            $airport->delete();
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
            $airport = Airport::where('code', $code)->firstOrFail();

            $flights = Flight::where('origin_airport_id', $airport->id)
                ->orWhere('destination_airport_id', $airport->id)
                ->with(['airline', 'origin', 'destination', 'airplane'])
                ->paginate(15);

            return Response::success(new FlightCollection($flights), 'Flights retrieved');

        } catch (\Throwable $e) {
            Log::error('AIRPORT FLIGHTS ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve flights for airport', 500);
        }
    }

    
}