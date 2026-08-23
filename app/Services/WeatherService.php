<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function getWeatherByCity(String $city)
    {
        return Http::get('https://api.weatherapi.com/v1/current.json', [
        'key' => env('WEATHER_API_KEY'),
        'q'   => $city
        ])->json();
    }
}
