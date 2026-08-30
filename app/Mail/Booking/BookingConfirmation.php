<?php

namespace App\Mail\Booking;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class BookingConfirmation extends Mailable 
{
    use Queueable, SerializesModels;
    public $booking;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Confirmation - ' . $this->booking->booking_reference,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.booking-created',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        try {
            Log::info('Generating PDF for booking', [
                'booking_id' => $this->booking->id,
                'reference' => $this->booking->booking_reference
            ]);

            if (!view()->exists('admin.bookings.pdf')) {
                Log::error('PDF view not found: admin.bookings.pdf');
                return [];
            }

            $pdf = PDF::loadView('admin.bookings.pdf', [
                'booking' => $this->booking
            ]);

            Log::info('PDF generated successfully');

            return [
                Attachment::fromData(fn () => $pdf->output(), 'booking-confirmation.pdf')
                    ->withMime('application/pdf'),
            ];

        } catch (\Exception $e) {
            Log::error('PDF Generation Failed: ' . $e->getMessage());
            Log::error('PDF Error Trace: ' . $e->getTraceAsString());
            return [];
        }
    }

}
