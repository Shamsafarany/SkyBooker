<?php

namespace App\StateMachines;

use App\Models\Airplane;

class AirplaneStatusStateMachine
{
    protected array $allowedTransitions = [
        'active'      => ['inactive', 'maintenance', 'retired'],
        'inactive'    => ['active', 'maintenance', 'retired'],
        'maintenance' => ['active', 'inactive', 'retired'],
        'retired'     => [], 
    ];

    public function __construct(public Airplane $airplane) {}

    public function transitionTo(string $newStatus): bool
    {
        $current = $this->airplane->status;

        return in_array($newStatus, $this->allowedTransitions[$current] ?? []);
    }
}

