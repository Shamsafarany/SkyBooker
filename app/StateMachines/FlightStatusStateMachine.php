<?php

namespace App\StateMachines;

use App\Models\Flight;

class FlightStatusStateMachine
{
    protected array $allowedTransitions = [
        'scheduled' => ['open', 'cancelled', 'delayed'],
        'open'      => ['closing', 'cancelled', 'delayed'],
        'closing'   => ['completed', 'cancelled', 'delayed'],
        'completed' => [], // final
        'cancelled' => [], // final
        'delayed'   => ['open', 'closing', 'cancelled'], 
    ];

    public function __construct(public Flight $flight) {}

    public function transitionTo(string $newStatus): bool
    {
        $current = $this->flight->status;

        return in_array($newStatus, $this->allowedTransitions[$current] ?? []);
    }
}
