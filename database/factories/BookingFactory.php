<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking;
use App\Models\User;
use App\Models\Flight;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $flight = Flight::inRandomOrder()->first() ?? Flight::factory()->create();
        $user = User::where('role', 'passenger')->inRandomOrder()->first()
                ?? User::factory()->create(['role' => 'passenger']);

        $seats = $this->faker->numberBetween(1, 4);
        $price = $flight->price * $seats;
        
        return [
            'user_id' => $user->id,
            'flight_id' => $flight->id,
            'booking_reference' => $this->generateReference(),
            'number_of_seats' => $seats,
            'total_price' => $price,
            'status' => $status = $this->faker->randomElement([
            'pending', 'pending', 'pending', 
            'confirmed', 'confirmed', 'confirmed', 'confirmed',
            'cancelled',
            'completed', 'completed'
        ]),
            'booking_date' => $this->faker->dateTimeBetween('-30 days', '+30 days'),
            'confirmed_at' => null,
            'cancelled_at' => null,
            'notes' => $this->faker->optional(0.3)->sentence(),
            'special_requests' => $this->faker->optional(0.2)->randomElement([
                'Wheelchair assistance needed',
                'Frequent flyer number: FF' . $this->faker->bothify('######'),
                'Accompanying minor',
                'Pet in cabin',
            ]),
        ];
    }
    private function generateReference(): string
    {
        $reference = 'BK-' . date('Y') . '-' . strtoupper($this->faker->bothify('??????'));
        
        // Ensure it's unique (fallback)
        while (Booking::where('booking_reference', $reference)->exists()) {
            $reference = 'BK-' . date('Y') . '-' . strtoupper($this->faker->bothify('??????'));
        }
        
        return $reference;
    }
    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => 'pending',
            'confirmed_at' => null,
            'cancelled_at' => null,
        ]);
    }

    public function confirmed(): static
    {
        return $this->state(fn () => [
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'cancelled_at' => null,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status' => 'cancelled',
            'confirmed_at' => $this->faker->optional()->dateTimeBetween('-10 days', 'now'),
            'cancelled_at' => now(),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => 'completed',
            'confirmed_at' => now()->subDays(rand(1, 10)),
            'cancelled_at' => null,
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn () => [
            'status' => 'failed',
            'confirmed_at' => null,
            'cancelled_at' => null,
        ]);
    }

    public function refunded(): static
    {
        return $this->state(fn () => [
            'status' => 'refunded',
            'confirmed_at' => now()->subDays(rand(1, 10)),
            'cancelled_at' => now()->subDays(rand(1, 5)),
        ]);
    }
}
