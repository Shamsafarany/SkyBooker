<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Queue\SerializesModels;

class GenerateBookingPdf implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public string $view;
    public string $filename;

    public $timeout = 120;
    public $tries = 3;
    /**
     * Create a new job instance.
     */
    
    public function __construct(Booking $booking, string $view, string $filename)
    {
        $this->booking = $booking;
        $this->view = $view;
        $this->filename = $filename;
        
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $pdf = Pdf::loadView($this->view, [
            'booking' => $this->booking
        ]);

        return $pdf->output();
    }
}
