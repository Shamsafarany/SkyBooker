<?php

namespace App\Jobs;

use App\Mail\ResetPasswordSuccessMail;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendPasswordResetSuccessEmailJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        Log::info('Sending password reset success email', [
            'user_id' => $this->user->id,
            'email' => $this->user->email,
        ]);

        Mail::to($this->user->email)
            ->send(new ResetPasswordSuccessMail($this->user->username));

        LogService::auth('Password reset success email sent', [
            'user_id' => $this->user->id,
        ]);
    }
}
