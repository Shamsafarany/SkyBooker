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
}
