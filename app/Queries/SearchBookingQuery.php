<?php

namespace App\Queries;

use App\Models\Booking;

class SearchBookingQuery
{
    public function execute(string $q)
    {
        return Booking::where('booking_reference', 'LIKE', "%{$q}%")
            ->orWhereHas('flight', function ($query) use ($q) {
                $query->where('flight_number', 'LIKE', "%{$q}%");
            })
            ->orWhereHas('user', function ($query) use ($q) {
                $query->where('username', 'LIKE', "%{$q}%")
                    ->orWhere('email', 'LIKE', "%{$q}%");
            })
            ->get();
    }
}
