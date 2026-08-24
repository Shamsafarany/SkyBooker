<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
class WeatherService
{
    public function getWeatherByCity(string $city)
    {
        try {
            $cacheKey = 'weather_' . strtolower($city);
            return Cache::remember($cacheKey, 1800, function () use ($city) {
            $response = Http::timeout(10)->get('https://api.weatherapi.com/v1/current.json', [
                'key' => env('WEATHER_API_KEY'),
                'q' => $city,
            ]);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return null;
            });
            
        } catch (\Exception $e) {
            Log::error('Weather API failed: ' . $e->getMessage());
            return null;
        }
    }
}
