<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    /** @use HasFactory<\Database\Factories\AirlinesFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'country',
        'logo',
        'website',
    ];

    public function flights()
    {
        return $this->hasMany(Flight::class);
    }

    public function getTotalFlightsAttribute(): int
    {
        return $this->flights()->count();
    }

    public function getLabelAttribute(): string
    {
        return "{$this->name} ({$this->code})";
    }
}
