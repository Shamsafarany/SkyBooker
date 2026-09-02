<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\WelcomeEmail;
use App\Services\LogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\URL;
use SebastianBergmann\Type\TrueType;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public Mailable $mailable;
    public User $user;
    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user= $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
            [
                'id' => $this->user->id,
                'hash' => sha1($this->user->email),
            ]
        );
        try {
            $mailable = new WelcomeEmail($this->user, $verificationUrl);

            Mail::to($this->user->email)->send($mailable);

            Log::info('Welcome email sent successfully', [
                'email' => $this->user->email
            ]);
            
        } catch (\Exception $e) {
            Log::error('Registration Mail failed: ' . $e->getMessage());
        } 
    
    }
}
