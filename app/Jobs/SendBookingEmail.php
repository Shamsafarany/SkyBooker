<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;

class SendBookingEmail implements ShouldQueue
{
    use Queueable, SerializesModels;
    public Mailable $mailable;
    public string $email;

    /**
     * Create a new job instance.
     */
    public function __construct(string $email, Mailable $mailable)
    {
        $this->email = $email;
        $this->mailable = $mailable;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            Mail::to($this->email)->send($this->mailable);
        }  catch (\Exception $e) {
            Log::error('SMTP ERROR: ' . $e->getMessage());
            throw $e;
        }
        
    }
}
