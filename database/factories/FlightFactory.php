<?php

namespace Database\Factories;

use App\Models\Flight;
use App\Models\Airport;
use App\Models\Airplane;
use App\Models\Airline;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Flight>
 */
class FlightFactory extends Factory
{
    public function definition(): array
    {   

        $airplane = Airplane::inRandomOrder()->first();
        $airline  = Airline::inRandomOrder()->first();
        $origin = Airport::inRandomOrder()->first();
        $destination = Airport::inRandomOrder()->first();
        while ($destination->id === $origin->id) {
            $destination = Airport::inRandomOrder()->first();
        }

        $durations = [
            '1h 30m', '2h 00m', '2h 30m', '3h 00m',
            '4h 00m', '5h 00m', '6h 00m', '7h 30m', '8h 30m'
        ];
        $duration = $this->faker->randomElement($durations);
        $departure = $this->faker->dateTimeBetween('now', '+3 months');

        preg_match('/(\d+)h\s(\d+)m/', $duration, $matches);
        $hours = (int)$matches[1];
        $minutes = (int)$matches[2];

        $arrival = (clone $departure)->modify("+{$hours} hours +{$minutes} minutes");
        $bookedSeats = $this->faker->numberBetween(0, (int)($airplane->capacity * 0.8));
        $availableSeats = $airplane->capacity - $bookedSeats;

        $now = now();
        $status = match (true) {
            $departure > $now && $availableSeats > 0 => 'open',
            $departure > $now && $availableSeats === 0 => 'closing',
            $departure < $now => 'completed',
            default => 'scheduled',
        };

        return [
            'flight_number' => $airline->code . $this->faker->unique()->numberBetween(100, 999),

            'origin_airport_id' => $origin->id,
            'destination_airport_id' => $destination->id,

            'airplane_id' => $airplane->id,
            'airline_id'  => $airline->id,

            'departure_date' => $departure->format('Y-m-d'),
            'departure_time' => $departure->format('H:i'),
            'arrival_date' => $arrival->format('Y-m-d'),
            'arrival_time'   => $arrival->format('H:i'),

            'duration' => $duration,
            'price' => $this->faker->numberBetween(100, 900),

            'total_seats' => $airplane->capacity,
            'booked_seats' => $bookedSeats,
            'available_seats' => $availableSeats,

            'status' => $status,
            'booking_deadline' => $departure->modify('-2 days'),

        ];
        
    }
    public function newFlight(): static
    {
        return $this->state(fn ($attributes) => [
            'status' => 'scheduled',
            'booked_seats' => 0,
            'available_seats' => $attributes['total_seats'],
            'booking_deadline' => null,  
        ]);
    }
}
