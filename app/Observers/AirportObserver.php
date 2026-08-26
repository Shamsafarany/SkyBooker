<?php

namespace App\Observers;

use App\Models\Airport;
use Illuminate\Support\Facades\Cache;
use App\Services\LogService;

class AirportObserver
{
    public function created(Airport $airport)
    {
        try {
            Cache::forget('api.airports.list');
            Cache::forget("api.airports.show.{$airport->id}");

            LogService::system("AIRPORT CREATED: Cache cleared", [
                'airport_id' => $airport->id,
                'code' => $airport->code,
            ]);

        } catch (\Throwable $e) {
            LogService::error('system', "AIRPORT OBSERVER ERROR (created)", [
                'airport_id' => $airport->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updated(Airport $airport)
    {
        try {
            Cache::forget('api.airports.list');
            Cache::forget("api.airports.show.{$airport->id}");

            LogService::system("AIRPORT UPDATED: Cache cleared", [
                'airport_id' => $airport->id,
                'code' => $airport->code,
                'changes' => $airport->getChanges(),
            ]);

            if ($airport->status === 'closed') {
                LogService::warning('system', "AIRPORT STATUS CHANGED TO CLOSED", [
                    'airport_id' => $airport->id,
                    'code' => $airport->code,
                ]);
            }

        } catch (\Throwable $e) {
            LogService::error('system', "AIRPORT OBSERVER ERROR (updated)", [
                'airport_id' => $airport->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function deleted(Airport $airport)
    {
        try {
            Cache::forget('api.airports.list');
            Cache::forget("api.airports.show.{$airport->id}");

            LogService::system("AIRPORT DELETED: Cache cleared", [
                'airport_id' => $airport->id,
                'code' => $airport->code,
            ]);

        } catch (\Throwable $e) {
            LogService::error('system', "AIRPORT OBSERVER ERROR (deleted)", [
                'airport_id' => $airport->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
