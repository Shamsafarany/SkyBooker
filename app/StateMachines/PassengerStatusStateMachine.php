<?php

namespace App\StateMachines;

use App\Models\Passenger;

class PassengerStatusStateMachine
{
    protected array $allowedTransitions = [
        'pending'     => ['confirmed', 'cancelled'],
        'confirmed'   => ['checked_in', 'cancelled'],
        'checked_in'  => ['boarded', 'cancelled'],
        'boarded'     => [], // final
        'cancelled'   => [], // final
    ];

    public function __construct(public Passenger $passenger) {}

    public function transitionTo(string $newStatus): bool
    {
        $current = $this->passenger->status;

        return in_array($newStatus, $this->allowedTransitions[$current] ?? []);
    }
}
