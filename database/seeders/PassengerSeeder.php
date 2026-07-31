<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Passenger;
use App\Models\Booking;
use App\Models\Flight;

class PassengerSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = Booking::all();
        foreach ($bookings as $booking) {
            Passenger::factory()
                ->count($booking->number_of_seats) 
                ->create([
                    'booking_id' => $booking->id
                ]);
        }

    }
}
