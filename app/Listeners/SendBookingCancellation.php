<?php

namespace App\Listeners;

use App\Events\BookingCancelled;
use App\Jobs\SendBookingEmail;
use App\Mail\Booking\BookingCancellation;

class SendBookingCancellation
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
    public function handle(BookingCancelled $event): void
    {
        dispatch(new SendBookingEmail(
            $event->booking->user->email,
            new BookingCancellation($event->booking)
        ));
    }
}
