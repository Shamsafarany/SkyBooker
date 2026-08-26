<?php

namespace App\Observers;

use App\Models\Flight;
use Illuminate\Support\Facades\Cache;
use App\Services\LogService;

class FlightObserver
{
    public function created(Flight $flight)
    {
        try {
            Cache::forget("api.flights.{$flight->id}");
            LogService::system("FLIGHT CREATED: Cache cleared", [
                'flight_id' => $flight->id,
                'flight_number' => $flight->flight_number,
            ]);

        } catch (\Throwable $e) {
            LogService::error('system', "FLIGHT OBSERVER ERROR (created)", [
                'flight_id' => $flight->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updated(Flight $flight)
    {
        try {
            Cache::forget("api.flights.{$flight->id}");

            LogService::system("FLIGHT UPDATED: Cache cleared", [
                'flight_id' => $flight->id,
                'changes' => $flight->getChanges(),
            ]);

            if ($flight->status === 'cancelled') {
                LogService::warning('system', "FLIGHT CANCELLED", [
                    'flight_id' => $flight->id,
                    'flight_number' => $flight->flight_number,
                ]);
            }

        } catch (\Throwable $e) {
            LogService::error('system', "FLIGHT OBSERVER ERROR (updated)", [
                'flight_id' => $flight->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function deleted(Flight $flight)
    {
        try {
            Cache::forget("api.flights.{$flight->id}");

            LogService::system("FLIGHT DELETED: Cache cleared", [
                'flight_id' => $flight->id,
                'flight_number' => $flight->flight_number,
            ]);

        } catch (\Throwable $e) {
            LogService::error('system', "FLIGHT OBSERVER ERROR (deleted)", [
                'flight_id' => $flight->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
