<?php

namespace App\Queries;

use App\Models\Flight;

class SearchFlightQuery
{
    public function execute(string $q)
{
    return Flight::where('flight_number', 'LIKE', "%{$q}%")
        ->orWhere('departure_date', 'LIKE', "%{$q}%")
        ->orWhereHas('origin', function ($query) use ($q) {
            $query->where('code', 'LIKE', "%{$q}%")
                    ->orWhere('city', 'LIKE', "%{$q}%")
                    ->orWhere('name', 'LIKE', "%{$q}%");
        })
        ->orWhereHas('destination', function ($query) use ($q) {
            $query->where('code', 'LIKE', "%{$q}%")
                    ->orWhere('city', 'LIKE', "%{$q}%")
                    ->orWhere('name', 'LIKE', "%{$q}%");
        })
        ->orWhereHas('airline', function ($query) use ($q) {
            $query->where('code', 'LIKE', "%{$q}%")
            ->orWhere('name', 'LIKE', "%{$q}%");
        })
        ->limit(10)
        ->get();
}
}
