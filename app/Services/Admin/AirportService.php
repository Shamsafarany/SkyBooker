<?php

namespace App\Services\Admin;

use App\Models\Airport;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

    public function getApiList()
    {
        return Cache::remember('api.airports.list', 60, function () {
            Log::info('API AIRPORT LIST: Cache MISS');

            $airports = Airport::withCount(['departingFlights', 'arrivingFlights'])->get();

            return $airports;
        });
    }
    public function getApiShow(Airport $airport)
    {
        $key = "api.airports.show.{$airport->id}";

        return Cache::remember($key, 60, function () use ($airport) {
            Log::info("API AIRPORT SHOW: Cache MISS for ID {$airport->id}");

            return Airport::withCount(['departingFlights', 'arrivingFlights'])
                ->findOrFail($airport->id);
        });
    }
    public function getFlightsForAirport(string $code)
    {
        $airport = Airport::where('code', $code)->firstOrFail();

        return [
            'airport' => $airport,
            'flights' => $airport->flights()->paginate(15)
        ];
    }

}
