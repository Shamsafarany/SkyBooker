<?php

namespace App\Observers;

use App\Models\Airplane;
use Illuminate\Support\Facades\Cache;
use App\Services\LogService;

class AirplaneObserver
{
    public function created(Airplane $airplane)
    {
        try {
            Cache::forget('api.airplanes.list');
            Cache::forget("api.airplanes.show.{$airplane->id}");
            Cache::forget('admin.airplanes.stats');

            LogService::system("AIRPLANE CREATED: Cache cleared", [
                'airplane_id' => $airplane->id,
                'model' => $airplane->model,
                'registration' => $airplane->registration,
            ]);

        } catch (\Throwable $e) {
            LogService::error('system', "AIRPLANE OBSERVER ERROR (created)", [
                'airplane_id' => $airplane->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updated(Airplane $airplane)
    {
        try {
            Cache::forget('api.airplanes.list');
            Cache::forget("api.airplanes.show.{$airplane->id}");
            Cache::forget('admin.airplanes.stats');

            LogService::system("AIRPLANE UPDATED: Cache cleared", [
                'airplane_id' => $airplane->id,
                'changes' => $airplane->getChanges(),
            ]);

            if ($airplane->status === 'maintenance') {
                LogService::warning('system', "AIRPLANE MOVED TO MAINTENANCE", [
                    'airplane_id' => $airplane->id,
                    'registration' => $airplane->registration,
                ]);
            }

        } catch (\Throwable $e) {
            LogService::error('system', "AIRPLANE OBSERVER ERROR (updated)", [
                'airplane_id' => $airplane->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function deleted(Airplane $airplane)
    {
        try {
            Cache::forget('api.airplanes.list');
            Cache::forget("api.airplanes.show.{$airplane->id}");
            Cache::forget('admin.airplanes.stats');

            LogService::system("AIRPLANE DELETED: Cache cleared", [
                'airplane_id' => $airplane->id,
                'registration' => $airplane->registration,
            ]);

        } catch (\Throwable $e) {
            LogService::error('system', "AIRPLANE OBSERVER ERROR (deleted)", [
                'airplane_id' => $airplane->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
