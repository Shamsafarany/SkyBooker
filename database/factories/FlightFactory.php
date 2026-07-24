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
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   

        $airplane = Airplane::inRandomOrder()->first();
        $airline  = Airline::inRandomOrder()->first();
        $origin = Airport::inRandomOrder()->first();
        $destination = Airport::inRandomOrder()->first();
        while ($destination->id === $origin->id) {
            $destination = Airport::inRandomOrder()->first();
        }
        $bookedSeats = $this->faker->numberBetween(0, (int)($airplane->capacity * 0.8));

        return [
            'flight_number' => $airline->code . $this->faker->unique()->numberBetween(100, 999),
            'origin_airport_id' => $origin->id,
            'destination_airport_id'=> $destination->id,
            'airplane_id' => $airplane->id,
            'airline_id'  => $airline->id,
            'departure_date' => $this->faker->dateTimeBetween('now', '+3 months'),
            'departure_time' => $this->faker->time('H:i:s'),
            'arrival_time' => $this->faker->time('H:i:s'),
            'duration' => $this->faker->randomElement(['5h 30m', '7h 15m', '8h 45m']),
            'price' => $this->faker->numberBetween(100, 900),
            'total_seats' => $airplane->capacity,
            'booked_seats' => $bookedSeats,
            'available_seats' => $airplane->capacity - $bookedSeats,
            'status' => $this->faker->randomElement(['new','scheduled', 'open', 'closing', 'completed', 'cancelled']),
            'booking_deadline' => $this->faker->optional()->dateTimeBetween('now', '+30 days'),

        ];
        
    }
    public function newFlight(): static
    {
        return $this->state(fn ($attributes) => [
            'status' => 'scheduled',
            'booked_seats' => 0,
            'available_seats' => $attributes['total_seats'],
            'booking_deadline' => null,  // No deadline yet
        ]);
    }
}
