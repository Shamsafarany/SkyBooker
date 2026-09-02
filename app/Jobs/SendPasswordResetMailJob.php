<?php

namespace App\Jobs;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPasswordResetMailJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public User $user;
    public string $token;

    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function handle()
    {

        Mail::to($this->user->email)->send(new ResetPasswordMail($this->token));

        LogService::auth('LOGIN MAIL SENT', ['email' => $this->user->email]);
    }
}
