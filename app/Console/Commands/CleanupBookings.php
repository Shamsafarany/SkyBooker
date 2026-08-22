<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Booking;

class CleanupBookings extends Command
{
    protected $signature = 'bookings:cleanup';
    protected $description = 'Delete cancelled bookings.';
    public function handle()
    {
        $deleted = Booking::where('status', 'cancelled')
            ->where('created_at', '<', now()->subDays(1))
            ->delete();
        
        $this->info("Deleted {$deleted} old cancelled bookings.");
    }
}
