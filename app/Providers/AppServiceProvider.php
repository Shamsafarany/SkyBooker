<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use App\Models\Airport;
use App\Models\Airplane;
use App\Models\Flight;
use App\Models\Booking;
use App\Models\Passenger;
use App\Models\Ticket;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        //observers
        Airport::observe(\App\Observers\AirportObserver::class);
        Airplane::observe(\App\Observers\AirplaneObserver::class);
        Flight::observe(\App\Observers\FlightObserver::class);
        Booking::observe(\App\Observers\BookingObserver::class); 
        Passenger::observe(\App\Observers\PassengerObserver::class); 
        Ticket::observe(\App\Observers\TicketObserver::class); 
        
        //macro
        Response::macro('success', function ($data, $message = 'OK') {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'data' => $data
            ]);
        });

        Response::macro('error', function ($message, $code = 400) {
            return response()->json([
                'status' => 'error',
                'message' => $message
            ], $code);
        });
    }
}
