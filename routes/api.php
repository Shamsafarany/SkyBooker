<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AirportController;
use App\Http\Controllers\Api\V1\AirlineController;
use App\Http\Controllers\Api\V1\AirplaneController;
use App\Http\Controllers\Api\V1\FlightController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\PassengerController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Response;


Route::prefix('v1')->name('api.v1.')->group(function() {
    Route::middleware('throttle:60,1')->group(function() {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::apiResource('airports', AirportController::class);
        Route::apiResource('airplanes', AirplaneController::class);
        Route::apiResource('airlines', AirlineController::class);
        Route::apiResource('flights', FlightController::class);
        Route::get('/airports/{airport}/flights', [AirportController::class, 'flights'])
                ->name('airports.flights');
        Route::middleware('auth:sanctum')->group(function() {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/user', [AuthController::class, 'user']);
            Route::apiResource('bookings', BookingController::class);
            Route::apiResource('passengers', PassengerController::class);
            Route::apiResource('tickets', TicketController::class);
            
            Route::get('flights/{flight}/bookings', [FlightController::class, 'bookings'])->name('flights.bookings');
            Route::get('/bookings/{booking}/passengers', [BookingController::class, 'passengers'])
                ->name('bookings.passengers');
            Route::get('/bookings/{booking}/tickets', [BookingController::class, 'tickets'])
                ->name('bookings.tickets');
            Route::get('/flights/{flight}/tickets', [FlightController::class, 'tickets'])
                ->name('flights.tickets');
            Route::get('/passengers/{passenger}/ticket', [PassengerController::class, 'ticket'])
                ->name('passengers.ticket');
        });
    }); 
    Route::fallback(function () {
    return Response::error('Endpoint not found', 404);
});

});