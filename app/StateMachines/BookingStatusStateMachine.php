<?php

namespace App\StateMachines;

use App\Models\Booking;

class BookingStatusStateMachine
{
    protected array $allowedTransitions = [
        'pending'   => ['confirmed', 'cancelled', 'failed'],
        'confirmed' => ['completed', 'cancelled', 'refunded'],
        'completed' => ['refunded'],
        'cancelled' => [],
        'failed'    => [],
        'refunded'  => [],
    ];

    public function __construct(public Booking $booking) {}

    public function transitionTo(string $newStatus): bool
    {
        $current = $this->booking->status;

        return in_array($newStatus, $this->allowedTransitions[$current] ?? []);
    }
}
