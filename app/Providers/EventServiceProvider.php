<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Event;
use App\Events\BookingCreated;
use App\Events\BookingCancelled;
use App\Events\FlightUpdated;
use App\Events\PasswordResetCompleted;
use App\Events\PasswordResetRequested;
use App\Events\UserRegistered;
use App\Listeners\SendBookingConfirmationEmail;
use App\Listeners\SendBookingCancellation;
use App\Listeners\GenerateBookingPdfListener;
use App\Listeners\NotifyPassengersOfFlightUpdate;
use App\Listeners\SendPasswordResetLink;
use App\Listeners\SendPasswordResetSuccessMail;
use App\Listeners\SendWelcomeEmailListener;

class EventServiceProvider extends ServiceProvider
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
        Event::listen(
            BookingCreated::class,
            [SendBookingConfirmationEmail::class, 'handle']
        );
        
        Event::listen(
            BookingCreated::class,
            [GenerateBookingPdfListener::class, 'handle']
        );
        
        Event::listen(
            BookingCancelled::class,
            [SendBookingCancellation::class, 'handle']
        );

        Event::listen(
            UserRegistered::class,
            [SendWelcomeEmailListener::class, 'handle']
        );

        Event::listen(
            FlightUpdated::class,
            [NotifyPassengersOfFlightUpdate::class, 'handle']
        );
        Event::listen(
            PasswordResetRequested::class,
            [SendPasswordResetLink::class, 'handle']
        );
        Event::listen(
            PasswordResetCompleted::class,
            [SendPasswordResetSuccessMail::class, 'handle']
        );
    }
}
