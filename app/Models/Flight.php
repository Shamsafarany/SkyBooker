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
    protected $casts = [
        'departure_date' => 'date',
        'booking_deadline' => 'datetime',
        'price' => 'decimal:2',
    ];

    //routes
    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }
    public function origin()
    {
        return $this->belongsTo(Airport::class, 'origin_airport_id');
    }
    public function destination()
    {
        return $this->belongsTo(Airport::class, 'destination_airport_id');
    }

    
    public function airplane()
    {
        return $this->belongsTo(Airplane::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function getRouteAttribute(): string
    {
        return "{$this->origin->code} → {$this->destination->code}";
    }

    public function getFullRouteAttribute(): string
    {
        return "{$this->origin->city} → {$this->destination->city}";
    }

    public function getAvailableSeatsAttribute(): int
    {
        return $this->total_seats - $this->booked_seats;
    }
}
