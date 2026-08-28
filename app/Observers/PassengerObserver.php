<?php

namespace App\Observers;

use App\Models\Passenger;
use Illuminate\Support\Facades\Cache;
use App\Services\LogService;

class PassengerObserver
{
    public function created(Passenger $passenger)
    {
        try {
            Cache::forget("api.passengers.show.{$passenger->id}");

            LogService::booking("PASSENGER CREATED", [
                'passenger_id' => $passenger->id,
                'booking_id' => $passenger->booking_id,
                'full_name' => $passenger->first_name . ' ' . $passenger->last_name,
                'status' => $passenger->status,
            ]);

        } catch (\Throwable $e) {
            LogService::error('booking', "PASSENGER OBSERVER ERROR (created)", [
                'passenger_id' => $passenger->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updated(Passenger $passenger)
    {
        try {
            Cache::forget("api.passengers.show.{$passenger->id}");

            $changes = $passenger->getChanges();

            LogService::booking("PASSENGER UPDATED", [
                'passenger_id' => $passenger->id,
                'changes' => $changes,
            ]);

            if (array_key_exists('status', $changes)) {
                LogService::warning('booking', "PASSENGER STATUS CHANGED", [
                    'passenger_id' => $passenger->id,
                    'old_status' => $passenger->getOriginal('status'),
                    'new_status' => $changes['status'],
                ]);
            }

        } catch (\Throwable $e) {
            LogService::error('system', "PASSENGER OBSERVER ERROR (updated)", [
                'passenger_id' => $passenger->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function deleted(Passenger $passenger)
    {
        try {
            Cache::forget("api.passengers.show.{$passenger->id}");

            LogService::booking("PASSENGER DELETED", [
                'passenger_id' => $passenger->id,
                'booking_id' => $passenger->booking_id,
            ]);

        } catch (\Throwable $e) {
            LogService::error('booking', "PASSENGER OBSERVER ERROR (deleted)", [
                'passenger_id' => $passenger->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
