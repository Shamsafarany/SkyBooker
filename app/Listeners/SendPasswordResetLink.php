<?php

namespace App\Listeners;

use App\Events\PasswordResetRequested;
use App\Jobs\SendPasswordResetMailJob;
use App\Mail\ResetPasswordMail;
use App\Services\LogService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPasswordResetLink
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
    public function handle(PasswordResetRequested $event)
    {
        LogService::auth('RESET EMAIL REQUESTED', ['email' => $event->user->email]);

        SendPasswordResetMailJob::dispatch($event->user, $event->token);
    }
}
