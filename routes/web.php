<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\AirplaneController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Mail\BookingCreated;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('guest')->group(function(){
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', LoginController::class)->name('login');
    Route::get('/forgot-password', function(){
        return view('auth.forgot-password');
    })->name('forgotPassword');
    Route::post('/forgot-password', [LoginController::class, 'sendResetLink'])->name('sendResetEmail');
    Route::get('/reset-password/{token}', function (string $token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('showResetPasswordForm');
    Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('resetPassword');
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', RegisterController::class)->name('register'); 
});

Route::middleware('auth')->group(function(){
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::prefix('admin')->name('admin.')->group(function() {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');    
        Route::resource('/airports', AirportController::class)->names('airports');
        Route::resource('/airplanes', AirplaneController::class)->names('airplanes');
        Route::get('/flights/{flight}/weather', [FlightController::class, 'weather']);
        Route::resource('/flights', FlightController::class)->names('flights');
        Route::get('/bookings/archive', [BookingController::class, 'archive'])->name('bookings.archive')->withTrashed(); 
        Route::post('/bookings/{booking}/restore', [BookingController::class, 'restore'])->name('bookings.restore')->withTrashed(); 
        Route::resource('/bookings', BookingController::class)->names('bookings')->withTrashed();
        Route::resource('/passengers', PassengerController::class)->names('passengers'); 
        Route::resource('/tickets', TicketController::class)->names('tickets');
        Route::get('/tickets/{ticket}/pdf', [TicketController::class, 'generatePDF'])->name('tickets.pdf');  
        Route::put('/profiles/password', [ProfileController::class, 'updatePassword'])->name('profiles.password');
        Route::resource('/profiles', ProfileController::class)
        ->only(['index', 'edit', 'update', 'destroy'])
        ->names([
            'index' => 'profiles.index',
            'edit' => 'profiles.edit',
            'update' => 'profiles.update',
            'destroy' => 'profiles.destroy',
        ]); 
        Route::get('/profiles/delete-confirm', [ProfileController::class, 'deleteConfirm'])
        ->name('profiles.delete.confirm');
    });
});


