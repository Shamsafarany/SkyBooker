<?php

namespace App\Observers;

use App\Models\Airplane;
use Illuminate\Support\Facades\Cache;
class AirplaneObserver
{
    /**
     * Handle the Airplane "created" event.
     */
    public function created(Airplane $airplane)
    {
        Cache::forget('api.airplanes.list');
        Cache::forget("api.airplanes.{$airplane->id}");
    }

    public function updated(Airplane $airplane)
    {
        Cache::forget('api.airplanes.list');
        Cache::forget("api.airplanes.{$airplane->id}");
    }

    public function deleted(Airplane $airplane)
    {
        Cache::forget('api.airplanes.list');
        Cache::forget("api.airplanes.{$airplane->id}");
    }

    /**
     * Handle the Airplane "restored" event.
     */
    public function restored(Airplane $airplane): void
    {
        //
    }

    /**
     * Handle the Airplane "force deleted" event.
     */
    public function forceDeleted(Airplane $airplane): void
    {
        //
    }
}
