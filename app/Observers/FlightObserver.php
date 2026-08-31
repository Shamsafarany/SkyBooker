<?php

namespace App\Observers;

use App\Events\FlightUpdated;
use App\Models\Flight;
use Illuminate\Support\Facades\Cache;
use App\Services\LogService;


class FlightObserver
{
    public function created(Flight $flight)
    {
        try {
            Cache::forget('api.flights.list');
            Cache::forget("api.flights.show.{$flight->id}");
            Cache::forget('admin.flights.stats');

            LogService::system("FLIGHT CREATED: Cache cleared", [
                'flight_id' => $flight->id,
                'flight_number' => $flight->flight_number,
                'airline_id' => $flight->airline_id,
                'origin_airport_id' => $flight->origin_airport_id,
                'destination_airport_id' => $flight->destination_airport_id,
                'departure_date' => $flight->departure_date,
                'arrival_date' => $flight->arrival_date,
                'total_seats' => $flight->total_seats,
                'available_seats' => $flight->available_seats,
                'booked_seats' => $flight->booked_seats,
                'price' => $flight->price,
                'status' => $flight->status,
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
            Cache::forget('api.flights.list');
            Cache::forget("api.flights.show.{$flight->id}");
            Cache::forget('admin.flights.stats');

            LogService::system("FLIGHT UPDATED: Cache cleared", [
                'flight_id' => $flight->id,
                'flight_number' => $flight->flight_number,
                'updated_fields' => $flight->getChanges(),
            ]);

            if ($flight->wasChanged('status')) {
                LogService::system("FLIGHT STATUS CHANGED", [
                    'flight_id' => $flight->id,
                    'flight_number' => $flight->flight_number,
                    'old_status' => $flight->getOriginal('status'),
                    'new_status' => $flight->status,
                ]);
            }

            if ($flight->wasChanged('price')) {
                LogService::system("FLIGHT PRICE CHANGED", [
                    'flight_id' => $flight->id,
                    'flight_number' => $flight->flight_number,
                    'old_price' => $flight->getOriginal('price'),
                    'new_price' => $flight->price,
                ]);
            }
            event(new FlightUpdated($flight));

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
            Cache::forget('api.flights.list');
            Cache::forget("api.flights.show.{$flight->id}");
            Cache::forget('admin.flights.stats');

            LogService::warning('system', "FLIGHT DELETED: Cache cleared", [
                'flight_id' => $flight->id,
                'flight_number' => $flight->flight_number,
                'status' => $flight->status,
                'total_seats' => $flight->total_seats,
                'booked_seats' => $flight->booked_seats,
            ]);

        } catch (\Throwable $e) {
            LogService::error('system', "FLIGHT OBSERVER ERROR (deleted)", [
                'flight_id' => $flight->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
