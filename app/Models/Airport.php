<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $fillable = [
        'name',
        'code',
        'city',
        'country',
        'terminals',
        'status',
    ];

    public function departingFlights()
    {
        return $this->hasMany(Flight::class, 'origin_airport_id');
    }
    public function arrivingFlights()
    {
        return $this->hasMany(Flight::class, 'destination_airport_id');
    }

    public function getLocationAttribute(): string
    {
        return "{$this->city}, {$this->country}";
    }

    public function getLabelAttribute(): string
    {
        return "{$this->name} ({$this->code})";
    }
}
