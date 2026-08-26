<?php

namespace App\Observers;

use App\Models\Airport;
use Illuminate\Support\Facades\Cache;
use App\Services\LogService;

class AirportObserver
{
    public function created(Airport $airport)
    {
        Cache::forget('api.airports.list');
        Cache::forget("api.airports.show.{$airport->id}");

        LogService::system("AIRPORT CREATED: Cache cleared", [
            'airport_id' => $airport->id,
            'code' => $airport->code,
        ]);
    }

    public function updated(Airport $airport)
    {
        Cache::forget('api.airports.list');
        Cache::forget("api.airports.show.{$airport->id}");

        LogService::system("AIRPORT UPDATED: Cache cleared", [
            'airport_id' => $airport->id,
            'code' => $airport->code,
            'changes' => $airport->getChanges(),
        ]);
    }

    public function deleted(Airport $airport)
    {
        Cache::forget('api.airports.list');
        Cache::forget("api.airports.show.{$airport->id}");

        LogService::system("AIRPORT DELETED: Cache cleared", [
            'airport_id' => $airport->id,
            'code' => $airport->code,
        ]);
    }
}
