<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Passenger;
use App\Models\Ticket;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        Passenger::all()->each(function ($passenger) {
            Ticket::factory()->create([
                'passenger_id' => $passenger->id,
            ]);
        });
    }
}