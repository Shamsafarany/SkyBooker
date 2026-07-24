<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::factory()->pending()->count(2)->create();
        Booking::factory()->confirmed()->count(20)->create();
        Booking::factory()->cancelled()->count(10)->create();
        Booking::factory()->completed()->count(50)->create();
        Booking::factory()->count(100)->create();
    }
}
