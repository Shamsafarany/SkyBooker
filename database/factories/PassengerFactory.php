<?php

namespace Database\Factories;

use App\Models\Passenger;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Passenger>
 */
class PassengerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'booking_id' => Booking::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->optional(0.8)->safeEmail(),
            'phone' => $this->faker->optional(0.7)->phoneNumber(),
            'passport_number' => $this->faker->bothify('??######?'),
            'nationality' => $this->faker->country(),
            'id_number' => $this->faker->bothify('####-######-####'),
            'date_of_birth' => $this->faker->optional(0.7)->dateTimeBetween('-60 years', '-18 years'),
        ];
    }
}
