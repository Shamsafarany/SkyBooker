<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airplane extends Model
{
    /** @use HasFactory<\Database\Factories\AirplaneFactory> */
    use HasFactory;

    protected $fillable = [
        'model',
        'manufacturer',
        'registration',
        'capacity',
        'year',
        'status',
    ];

    public function flights()
    {
        return $this->hasMany(Flight::class);
    }
    public function getLabelAttribute(): string
    {
        return "{$this->model} ({$this->registration})";
    }
}
