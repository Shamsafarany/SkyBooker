<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException; 
use Illuminate\Database\Eloquent\SoftDeletes; 

class Passenger extends Model
{
    /** @use HasFactory<\Database\Factories\PassengerFactory> */
    use HasFactory;
    use SoftDeletes; 
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
        'deleted_at' => 'datetime',
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

    public function setPassportNumberAttribute($value)
    {
        if ($value === null || $value === '') {
            $this->attributes['passport_number'] = null;
            return;
        }
        $this->attributes['passport_number'] = Crypt::encrypt($value);
    }

    public function getPassportNumberAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        try {
            return Crypt::decrypt($value);
        } catch (DecryptException $e) {
            return $value; 
        }
    }
    public function setIdNumberAttribute($value)
    {
        if ($value === null || $value === '') {
            $this->attributes['id_number'] = null;
            return;
        }
        $this->attributes['id_number'] = Crypt::encrypt($value);
    }

    public function getIdNumberAttribute($value)
    {
        if (!$value) {
            return null;
        }
        try {
            return Crypt::decrypt($value);
        } catch (DecryptException $e) {
            return $value; 
        }
    }


}




