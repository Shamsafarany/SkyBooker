<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Airport;
use App\Models\Airplane;
use App\Models\Flight;
use App\Models\Booking;
use App\Models\Passenger;
use App\Models\Ticket;
use App\Models\User;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Airport::observe(\App\Observers\AirportObserver::class);
        Airplane::observe(\App\Observers\AirplaneObserver::class);
        Flight::observe(\App\Observers\FlightObserver::class);
        Booking::observe(\App\Observers\BookingObserver::class); 
        Passenger::observe(\App\Observers\PassengerObserver::class); 
        Ticket::observe(\App\Observers\TicketObserver::class); 
        User::observe(\App\Observers\UserObserver::class);
    }
}
