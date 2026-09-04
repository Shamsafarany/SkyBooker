<?php

namespace App\StateMachines;

use App\Models\Airport;
use Exception;

class AirportStatusStateMachine
{
    protected array $allowedTransitions = [
        'active'      => ['maintenance', 'closed'],
        'maintenance' => ['active', 'closed'],
        'closed'      => ['active'],
    ];

    public function __construct(public Airport $airport) {}

    public function transitionTo(string $newStatus)
    {
        $current = $this->airport->status;

        return in_array($newStatus, $this->allowedTransitions[$current] ?? []);
    }
}
