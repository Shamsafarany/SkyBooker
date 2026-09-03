<?php

namespace App\Services\Admin;

use App\Queries\SearchFlightQuery;
use App\Queries\SearchAirportQuery;
use App\Queries\SearchBookingQuery;

class SearchService
{
    public function searchAirports(array $data)
    {
        return app(SearchAirportQuery::class)->execute($data['q'] ?? null);
    }

    public function searchFlights(array $data)
    {
        return app(SearchFlightQuery::class)->execute($data['q'] ?? null);
    }

    public function searchBookings(array $data)
    {
        return app(SearchBookingQuery::class)->execute($data['q'] ?? null);
    }
}

