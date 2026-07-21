<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\AirplaneController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\ReservationController;

use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('home');

Route::prefix('admin')->name('admin.')->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');    
    Route::resource('/airports', AirportController::class)->names('airports');
    Route::resource('/airplanes', AirplaneController::class)->names('airplanes');
    Route::resource('/flights', FlightController::class)->names('flights');
    Route::resource('/reservations', ReservationController::class)->names('reservations');  
});

