<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Ticket extends Model
{
    use HasFactory, SoftDeletes; 
    protected $fillable = [
        'passenger_id',
        'ticket_number',
        'seat_number',
        'class',
        'meal_preference',
        'issued_at',
        'notes',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public static function generateTicketNumber(): string
    {
        $prefix = 'TKT';
        $year = date('Y');
        $random = strtoupper(Str::random(6));

        $ticketNumber = $prefix . $year . $random;

        while (self::where('ticket_number', $ticketNumber)->exists()) {
            $random = strtoupper(Str::random(6));
            $ticketNumber = $prefix . $year . $random;
        }

        return $ticketNumber;
    }

    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }

    public function flight()
    {
        return $this->hasOneThrough(
            Flight::class,   
            Booking::class,   
            'id',          
            'id',          
            'passenger_id',   
            'booking_id'
        );
    }

    public function user()
{
    return $this->hasOneThrough(
        User::class,      
        Booking::class,   
        'id',       
        'id',  
        'passenger_id', 
        'booking_id'
    );
}
}
