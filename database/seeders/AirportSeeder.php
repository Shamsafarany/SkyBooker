<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Airport;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $airports = [
            [
                'name' => 'John F. Kennedy International Airport',
                'code' => 'JFK',
                'city' => 'New York',
                'country' => 'USA',
                'terminals' => 6,
                'status' => 'active',
            ],
            [
                'name' => 'Los Angeles International Airport',
                'code' => 'LAX',
                'city' => 'Los Angeles',
                'country' => 'USA',
                'terminals' => 9,
                'status' => 'active',
            ],
            [
                'name' => 'O\'Hare International Airport',
                'code' => 'ORD',
                'city' => 'Chicago',
                'country' => 'USA',
                'terminals' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'San Francisco International Airport',
                'code' => 'SFO',
                'city' => 'San Francisco',
                'country' => 'USA',
                'terminals' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'Miami International Airport',
                'code' => 'MIA',
                'city' => 'Miami',
                'country' => 'USA',
                'terminals' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'Dallas/Fort Worth International Airport',
                'code' => 'DFW',
                'city' => 'Dallas',
                'country' => 'USA',
                'terminals' => 5,
                'status' => 'active',
            ],
            [
                'name' => 'Denver International Airport',
                'code' => 'DEN',
                'city' => 'Denver',
                'country' => 'USA',
                'terminals' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'Atlanta Hartsfield-Jackson Airport',
                'code' => 'ATL',
                'city' => 'Atlanta',
                'country' => 'USA',
                'terminals' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'Heathrow Airport',
                'code' => 'LHR',
                'city' => 'London',
                'country' => 'UK',
                'terminals' => 5,
                'status' => 'active',
            ],
            [
                'name' => 'Paris Charles de Gaulle Airport',
                'code' => 'CDG',
                'city' => 'Paris',
                'country' => 'France',
                'terminals' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'Amsterdam Schiphol Airport',
                'code' => 'AMS',
                'city' => 'Amsterdam',
                'country' => 'Netherlands',
                'terminals' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'Frankfurt Airport',
                'code' => 'FRA',
                'city' => 'Frankfurt',
                'country' => 'Germany',
                'terminals' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'Adolfo Suárez Madrid–Barajas Airport',
                'code' => 'MAD',
                'city' => 'Madrid',
                'country' => 'Spain',
                'terminals' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'Rome Fiumicino Airport',
                'code' => 'FCO',
                'city' => 'Rome',
                'country' => 'Italy',
                'terminals' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'Dubrovnik Airport',
                'code' => 'DBV',
                'city' => 'Dubrovnik',
                'country' => 'Croatia',
                'terminals' => 1,
                'status' => 'maintenance',
            ],

            [
                'name' => 'Dubai International Airport',
                'code' => 'DXB',
                'city' => 'Dubai',
                'country' => 'UAE',
                'terminals' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'King Khalid International Airport',
                'code' => 'RHD',
                'city' => 'Riyadh',
                'country' => 'KSA',
                'terminals' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'Hamad International Airport',
                'code' => 'DOH',
                'city' => 'Doha',
                'country' => 'Qatar',
                'terminals' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'Abu Dhabi International Airport',
                'code' => 'AUH',
                'city' => 'Abu Dhabi',
                'country' => 'UAE',
                'terminals' => 3,
                'status' => 'active',
            ],

            [
                'name' => 'Tokyo Haneda Airport',
                'code' => 'HND',
                'city' => 'Tokyo',
                'country' => 'Japan',
                'terminals' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'Singapore Changi Airport',
                'code' => 'SIN',
                'city' => 'Singapore',
                'country' => 'Singapore',
                'terminals' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'Incheon International Airport',
                'code' => 'ICN',
                'city' => 'Seoul',
                'country' => 'South Korea',
                'terminals' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'Beijing Capital International Airport',
                'code' => 'PEK',
                'city' => 'Beijing',
                'country' => 'China',
                'terminals' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'Hong Kong International Airport',
                'code' => 'HKG',
                'city' => 'Hong Kong',
                'country' => 'China',
                'terminals' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'Kuala Lumpur International Airport',
                'code' => 'KUL',
                'city' => 'Kuala Lumpur',
                'country' => 'Malaysia',
                'terminals' => 2,
                'status' => 'active',
            ],

            [
                'name' => 'Sydney Kingsford Smith Airport',
                'code' => 'SYD',
                'city' => 'Sydney',
                'country' => 'Australia',
                'terminals' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'Melbourne Airport',
                'code' => 'MEL',
                'city' => 'Melbourne',
                'country' => 'Australia',
                'terminals' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'Auckland Airport',
                'code' => 'AKL',
                'city' => 'Auckland',
                'country' => 'New Zealand',
                'terminals' => 2,
                'status' => 'active',
            ],

            [
                'name' => 'Cape Town International Airport',
                'code' => 'CPT',
                'city' => 'Cape Town',
                'country' => 'South Africa',
                'terminals' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'OR Tambo International Airport',
                'code' => 'JNB',
                'city' => 'Johannesburg',
                'country' => 'South Africa',
                'terminals' => 2,
                'status' => 'active',
            ],

            [
                'name' => 'Toronto Pearson International Airport',
                'code' => 'YYZ',
                'city' => 'Toronto',
                'country' => 'Canada',
                'terminals' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'Vancouver International Airport',
                'code' => 'YVR',
                'city' => 'Vancouver',
                'country' => 'Canada',
                'terminals' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'Mexico City International Airport',
                'code' => 'MEX',
                'city' => 'Mexico City',
                'country' => 'Mexico',
                'terminals' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'São Paulo–Guarulhos International Airport',
                'code' => 'GRU',
                'city' => 'São Paulo',
                'country' => 'Brazil',
                'terminals' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'Ezeiza International Airport',
                'code' => 'EZE',
                'city' => 'Buenos Aires',
                'country' => 'Argentina',
                'terminals' => 2,
                'status' => 'active',
            ],
        ];


        foreach($airports as $airport){
            Airport::create($airport);
        }
        $this->command->info('Total airports: ' . Airport::count());
        $this->command->info('Active airports: ' . Airport::where('status', 'active')->count());
        $this->command->info('In maintenance: ' . Airport::where('status', 'maintenance')->count());
    }
}
