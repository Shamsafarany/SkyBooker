<?php

namespace App\Listeners;

use App\Events\FlightUpdated;
use App\Notifications\FlightUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyPassengersOfFlightUpdate implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(FlightUpdated $event): void
    {
        $flight = $event->flight;
        foreach($flight->bookings as $booking){
            $booking->user->notify(new FlightUpdatedNotification($flight));
        }
    }
}
