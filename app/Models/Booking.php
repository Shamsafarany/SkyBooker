<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'flight_id',
        'booking_reference',
        'number_of_seats',
        'total_price',
        'status',
        'booking_date',
        'confirmed_at',
        'cancelled_at',
        'notes',
        'special_requests',
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'total_price' => 'decimal:2',
    ];
}
