<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    /** @use HasFactory<\Database\Factories\PassengerFactory> */
    use HasFactory;
    protected $fillable = [
        'booking_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'passport_number',
        'nationality',
        'id_number',
        'date_of_birth',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    //relations
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function ticket()
    {
        return $this->hasOne(Ticket::class);
    }


    public function getFullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

}




