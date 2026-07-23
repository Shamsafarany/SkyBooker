<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    /** @use HasFactory<\Database\Factories\FlightFactory> */
    use HasFactory;

    protected $fillable = [
        'flight_number',
        'airline_id',
        'origin_airport_id',
        'destination_airport_id',
        'airplane_id',
        'departure_date',
        'departure_time',
        'arrival_time',
        'duration',
        'price',
        'total_seats',
        'booked_seats',
        'available_seats',
        'status',
        'booking_deadline',
    ];
}
