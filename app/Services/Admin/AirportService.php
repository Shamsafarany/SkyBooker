<?php

namespace App\Services\Admin;

use App\Filters\AirportFilter;
use App\Http\Resources\Api\V1\AirportCollection;
use App\Models\Airport;
use App\Models\Flight;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\Api\V1\AirportResource;
use App\StateMachines\AirportStatusStateMachine;
use Illuminate\Http\Request;

class AirportService
{
    public function getAllWithStats()
    {
        $airports = Airport::withCount(['departingFlights', 'arrivingFlights'])
            ->latest()
            ->get();

        $stats = Cache::remember('admin.airports.stats', 60, function () use ($airports) {

            Log::channel('booking')->info('ADMIN AIRPORT STATS: Cache MISS');

            return [
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
        });

        Log::channel('booking')->info('ADMIN AIRPORT STATS: Cache HIT');

        return compact(['airports', 'stats']);
    }

    public function create(array $data)
    {
        return Airport::create($data);
    }

    public function update(Airport $airport, array $data)
    {
        $airport->update($data);
        return $airport;
    }

    public function delete(Airport $airport)
    {
        $airport->delete();
    }

    public function getApiList(Request $request)
    {
        
        $query = Airport::query()->withCount(['departingFlights', 'arrivingFlights']);
        $query = (new AirportFilter())->apply($query, $request);

        if ($request->filled('sort')) {
        $direction = $request->input('direction', 'asc');
        $query->orderBy($request->sort, $direction);
        }
        
        $airports = $query->get();
        return new AirportCollection($airports);

    }
    public function getApiShow(Airport $airport)
    {
        $key = "api.airports.show.{$airport->code}";

        return Cache::remember($key, 60, function () use ($airport) {
            Log::info("AIRPORT SHOW: Cache MISS - querying database for CODE {$airport->code}");

            $airport = Airport::withCount(['departingFlights', 'arrivingFlights'])
                ->where('code', $airport->code)
                ->firstOrFail();

            // Convert to array BEFORE caching
            return (new AirportResource($airport))->resolve();
        });
    }

    public function getFlightsForAirport(string $code)
    {
        $airport = Airport::where('code', $code)->firstOrFail();

    $flights = Flight::where('origin_airport_id', $airport->id)
        ->orWhere('destination_airport_id', $airport->id)
        ->with(['airline', 'origin', 'destination', 'airplane'])
        ->paginate(15);

    return [
        'airport' => $airport,
        'flights' => $flights
    ];
    }

    public function changeStatus(Airport $airport, string $newStatus)
    {
        $stateMachine = new AirportStatusStateMachine($airport);

        if (! $stateMachine->transitionTo($newStatus)) {
            Log::warning("Invalid airport status transition: {$airport->status} → {$newStatus}");
            return null; 
        }

        $oldStatus = $airport->status;

        $airport->status = $newStatus;
        $airport->save();

        Log::info("Airport #{$airport->code} status changed: {$oldStatus} → {$newStatus}");

        return $airport;
    }



}
