<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendWelcomeEmail;
use App\Mail\WelcomeEmail;

class SendWelcomeEmailListener 
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
    public function handle(UserRegistered $event): void
    {
        dispatch(new SendWelcomeEmail($event->user->email, new WelcomeEmail($event->user)));
    
    }
}
