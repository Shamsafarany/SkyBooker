<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Passenger;

class EncryptPassengerData extends Command
{
    protected $signature = 'passengers:encrypt';
    protected $description = 'Encrypt existing passenger passport and ID numbers';
    public function handle()
    {
        $passengers = Passenger::all();
        foreach ($passengers as $passenger) {
            $passenger->save();
        }
        $this->info('All passenger data encrypted successfully!');
    }
}
