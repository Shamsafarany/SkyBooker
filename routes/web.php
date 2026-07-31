<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\AirplaneController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TicketController;

use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('home');

Route::prefix('admin')->name('admin.')->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');    
    Route::resource('/airports', AirportController::class)->names('airports');
    Route::resource('/airplanes', AirplaneController::class)->names('airplanes');
    Route::resource('/flights', FlightController::class)->names('flights');
    Route::resource('/bookings', BookingController::class)->names('bookings');  
    Route::resource('/tickets', TicketController::class)->names('tickets');  
});

