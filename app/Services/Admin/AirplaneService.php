<?php

namespace App\Services\Admin;

use App\Models\Airplane;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\Api\V1\AirplaneResource;

class AirplaneService
{

    public function getApiList()
    {
        return Cache::remember('api.airplanes.list', 60, function () {
            Log::info('AIRPLANE INDEX: Cache MISS - querying database');

            $airplanes = Airplane::withCount('flights')->get();
            return AirplaneResource::collection($airplanes)->resolve();
        });
    }

    public function getApiShow(Airplane $airplane)
    {
        $key = "api.airplanes.show.{$airplane->id}";

        return Cache::remember($key, 60, function () use ($airplane) {
            Log::info("AIRPLANE SHOW: Cache MISS - querying database for ID {$airplane->id}");
            $airplane->load('flights');

            return (new AirplaneResource($airplane))->resolve();
        });
    }

    public function create(array $data)
    {
        return Airplane::create($data);
    }

    public function update(Airplane $airplane, array $data)
    {
        $airplane->update($data);
        return $airplane;
    }

    public function delete(Airplane $airplane)
    {
        $airplane->delete();
    }
    public function getAllWithStats()
    {
        $airplanes = Airplane::withCount('flights')
            ->orderBy('manufacturer')
            ->orderBy('model')
            ->get();

        $stats = Cache::remember('admin.airplanes.stats', 60, function () use ($airplanes) {
            Log::channel('booking')->info('ADMIN AIRPLANE STATS: Cache MISS');

            return [
                'total' => $airplanes->count(),
                'active' => $airplanes->where('status', 'active')->count(),
                'inactive' => $airplanes->where('status', 'inactive')->count(),
                'maintenance' => $airplanes->where('status', 'maintenance')->count(),
                'total_capacity' => $airplanes->sum('capacity'),
                'total_flights' => $airplanes->sum('flights_count'),
            ];
        });
        Log::channel('booking')->info('ADMIN AIRPLANES STATS: Cache HIT');

        return compact(['airplanes', 'stats']);
    }
}
