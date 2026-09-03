<?php

namespace App\Queries;

use App\Models\Airport;

class SearchAirportQuery
{
    public function execute(string $q)
    {
        return Airport::where('name', 'LIKE', "%{$q}%")
            ->orWhere('code', 'LIKE', "%{$q}%")
            ->orWhere('city', 'LIKE', "%{$q}%")
            ->orWhere('country', 'LIKE', "%{$q}%")
            ->limit(10)
            ->get();
    }
}
