<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\Passenger;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $passenger = Passenger::inRandomOrder()->first() ?? Passenger::factory()->create();
        return [
            'passenger_id' => $passenger->id,
            'ticket_number' => strtoupper('TKT-' . $this->faker->bothify('########')),
            'seat_number' => $this->faker->optional()->bothify('##?'),
            'class' => $this->faker->randomElement([
                'economy',
                'premium_economy',
                'business',
                'first'
            ]),
            'meal_preference' => $this->faker->randomElement([
                'standard',
                'full_meal',
                'sandwitch',
                'child_meal',
                'none'
            ]),
            'issued_at' => now(),
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }
}
