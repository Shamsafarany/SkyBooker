<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Passenger;
use App\Models\Booking;

class PassengerSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = Booking::all();
        foreach ($bookings as $booking) {
            Passenger::factory()
                ->count(rand(1, 3)) 
                ->create([
                    'booking_id' => $booking->id
                ]);
        }

    }
}
