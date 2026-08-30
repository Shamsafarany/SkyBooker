<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Jobs\SendBookingEmail;
use App\Mail\Booking\BookingConfirmation;

class SendBookingConfirmationEmail
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
    public function handle(BookingCreated $event): void
    {
        dispatch(new SendBookingEmail(
            $event->booking->user->email,
            new BookingConfirmation($event->booking)
        ));
    }
}
