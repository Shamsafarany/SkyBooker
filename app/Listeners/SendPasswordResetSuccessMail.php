<?php

namespace App\Listeners;

use App\Events\PasswordResetCompleted;
use App\Jobs\SendPasswordResetSuccessEmailJob;
use App\Services\LogService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPasswordResetSuccessMail
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
    public function handle(PasswordResetCompleted $event)
    {
        LogService::auth('Dispatching password reset success email job', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
        ]);
        dispatch(new SendPasswordResetSuccessEmailJob(
            $event->user
        ));
    }
}
