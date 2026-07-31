<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Flight;


class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Flight::all()->each(function ($flight) {
            $remainingSeats = $flight->booked_seats;
            while ($remainingSeats > 0) {
                $seats = min($remainingSeats, rand(1, 4));
                Booking::factory()
                    ->state([
                        'flight_id' => $flight->id,
                        'number_of_seats' => $seats,
                        'total_price' => $seats * $flight->price,
                    ])
                    ->create();
                $remainingSeats -= $seats;
            }
        });
    }
}
