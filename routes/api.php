<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AirportController;
use App\Http\Controllers\Api\V1\AirplaneController;
use App\Http\Controllers\Api\V1\FlightController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\PassengerController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\AuthController;



Route::prefix('v1')->name('api.v1.')->group(function () {

    // PUBLIC ROUTES
    Route::middleware('throttle:auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::middleware('throttle:api')->group(function () {
        Route::get('/airports', [AirportController::class, 'index']);
        Route::get('/flights', [FlightController::class, 'index']);
        Route::get('/airports/{airport}/flights', [AirportController::class, 'flights']);
    });

    // CUSTOMER ROUTES
    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::apiResource('bookings', BookingController::class);
        Route::get('/bookings/{booking}/tickets', [BookingController::class, 'tickets']);
        Route::get('/bookings/{booking}/passengers', [BookingController::class, 'passengers']);
    });

    // ADMIN ROUTES
    Route::middleware(['auth:sanctum', 'admin', 'throttle:api'])->group(function () {

        Route::apiResource('airports', AirportController::class);
        Route::apiResource('airplanes', AirplaneController::class);
        Route::apiResource('flights', FlightController::class);
        Route::apiResource('passengers', PassengerController::class);
        Route::apiResource('tickets', TicketController::class);

        Route::post('/airports/{airport}/status', [AirportController::class, 'changeStatus']);
        Route::post('/airplanes/{airplane}/status', [AirplaneController::class, 'changeStatus']);
        Route::post('/flights/{flight}/status', [FlightController::class, 'changeStatus']);
        Route::post('/bookings/{booking}/status', [BookingController::class, 'changeStatus']);
        Route::post('/passengers/{passenger}/status', [PassengerController::class, 'changeStatus']);

        Route::get('/bookings/archive', [BookingController::class, 'archive']);
        Route::put('/bookings/{booking}/restore', [BookingController::class, 'restore']);
    });

});
