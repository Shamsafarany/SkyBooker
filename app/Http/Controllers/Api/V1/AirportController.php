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
}

    public function store(StoreAirportRequest $request)
    {
        $airport = Airport::create($request->validated());
        $airport->load(['departingFlights', 'arrivingFlights']);
        return response()->json(
            new AirportResource($airport),
            201
        );
    }

    public function show(Airport $airport)
    {
        $airportData = Cache::remember("api.airports.show.{$airport->id}",60, function () use ($airport) {
            Log::info('AIRPORT INDEX: Cache MISS - querying database');
            $airport = Airport::withCount([
                'departingFlights',
                'arrivingFlights'
            ])->findOrFail($airport->id);

            return (new AirportResource($airport))->resolve();
        }
    );
    Log::info('AIRPORT INDEX: Cache HIT - getting cache');
    return Response::success($airportData, 'Airport retrieved');
}

    public function update(UpdateAirportRequest $request,Airport $airport
    ) {
        $airport->update($request->validated());
        $airport->load(['departingFlights', 'arrivingFlights']);
        return new AirportResource($airport);
    }
    public function destroy(Airport $airport)
    {
        $airport->delete();
        return response()->json(null, 204);
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
    public function flights(String $code)
    {
        $airport = Airport::where('code', $code)->firstOrFail();

        $flights = Flight::where('origin_airport_id', $airport->id)
            ->orWhere('destination_airport_id', $airport->id)
            ->with(['airline', 'origin', 'destination', 'airplane'])
            ->paginate(15);

        return new FlightCollection($flights);
    }
}