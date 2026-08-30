<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\BookingCreated;
use App\Jobs\GenerateBookingPdf;

class GenerateBookingPdfListener
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
        dispatch(new GenerateBookingPDF(
            $event->booking,
            'admin.bookings.pdf',         
            'booking-confirmation.pdf'  
        ));
    }
}
