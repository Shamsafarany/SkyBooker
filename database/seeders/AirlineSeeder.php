<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Airline;

class AirlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $airlines = [
            [
                'name' => 'SkyBooker',
                'code' => 'SKY',
                'country' => 'UAE',
                'logo' => 'skybooker.png',
                'website' => 'https://skybooker.com',
            ],
            [
                'name' => 'Emirates',
                'code' => 'EK',
                'country' => 'UAE',
                'logo' => 'emirates.png',
                'website' => 'https://emirates.com',
            ],
            [
                'name' => 'Delta Air Lines',
                'code' => 'DL',
                'country' => 'USA',
                'logo' => 'delta.png',
                'website' => 'https://delta.com',
            ],
            [
                'name' => 'British Airways',
                'code' => 'BA',
                'country' => 'UK',
                'logo' => 'ba.png',
                'website' => 'https://britishairways.com',
            ],
            [
                'name' => 'Singapore Airlines',
                'code' => 'SQ',
                'country' => 'Singapore',
                'logo' => 'singapore.png',
                'website' => 'https://singaporeair.com',
            ],
            [
                'name' => 'Qatar Airways',
                'code' => 'QR',
                'country' => 'Qatar',
                'logo' => 'qatar.png',
                'website' => 'https://qatarairways.com',
            ],
            [
                'name' => 'Lufthansa',
                'code' => 'LH',
                'country' => 'Germany',
                'logo' => 'lufthansa.png',
                'website' => 'https://lufthansa.com',
            ],
            [
                'name' => 'Air France',
                'code' => 'AF',
                'country' => 'France',
                'logo' => 'airfrance.png',
                'website' => 'https://airfrance.com',
            ],
        ];

        foreach ($airlines as $airline) {
            Airline::create($airline);
        }

    }
}
