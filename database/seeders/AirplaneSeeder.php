<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Airplane;

class AirplaneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $airplanes = [
            [
                'model' => 'Boeing 737-800',
                'manufacturer' => 'Boeing',
                'registration' => 'N737AA',
                'capacity' => 189,
                'year' => 2018,
                'status' => 'active',
            ],
            [
                'model' => 'Boeing 737-800',
                'manufacturer' => 'Boeing',
                'registration' => 'N737BB',
                'capacity' => 189,
                'year' => 2019,
                'status' => 'active',
            ],
            [
                'model' => 'Boeing 737-800',
                'manufacturer' => 'Boeing',
                'registration' => 'N737CC',
                'capacity' => 189,
                'year' => 2020,
                'status' => 'active',
            ],
            [
                'model' => 'Boeing 737 MAX 9',
                'manufacturer' => 'Boeing',
                'registration' => 'N737MX',
                'capacity' => 220,
                'year' => 2022,
                'status' => 'active',
            ],
            [
                'model' => 'Boeing 737 MAX 9',
                'manufacturer' => 'Boeing',
                'registration' => 'N737MY',
                'capacity' => 220,
                'year' => 2023,
                'status' => 'active',
            ],
            [
                'model' => 'Boeing 777-300ER',
                'manufacturer' => 'Boeing',
                'registration' => 'N777GH',
                'capacity' => 396,
                'year' => 2021,
                'status' => 'active',
            ],
            [
                'model' => 'Boeing 777-300ER',
                'manufacturer' => 'Boeing',
                'registration' => 'N777HI',
                'capacity' => 396,
                'year' => 2022,
                'status' => 'active',
            ],
            [
                'model' => 'Boeing 787-9 Dreamliner',
                'manufacturer' => 'Boeing',
                'registration' => 'N787DC',
                'capacity' => 330,
                'year' => 2022,
                'status' => 'active',
            ],
            [
                'model' => 'Boeing 787-9 Dreamliner',
                'manufacturer' => 'Boeing',
                'registration' => 'N787DD',
                'capacity' => 330,
                'year' => 2023,
                'status' => 'active',
            ],
            [
                'model' => 'Boeing 767-300ER',
                'manufacturer' => 'Boeing',
                'registration' => 'N767AA',
                'capacity' => 280,
                'year' => 2016,
                'status' => 'maintenance',
            ],
            [
                'model' => 'Boeing 747-8',
                'manufacturer' => 'Boeing',
                'registration' => 'N747BB',
                'capacity' => 467,
                'year' => 2017,
                'status' => 'inactive',
            ],

            [
                'model' => 'Airbus A320neo',
                'manufacturer' => 'Airbus',
                'registration' => 'A320BB',
                'capacity' => 195,
                'year' => 2020,
                'status' => 'active',
            ],
            [
                'model' => 'Airbus A320neo',
                'manufacturer' => 'Airbus',
                'registration' => 'A320CC',
                'capacity' => 195,
                'year' => 2021,
                'status' => 'active',
            ],
            [
                'model' => 'Airbus A320neo',
                'manufacturer' => 'Airbus',
                'registration' => 'A320DD',
                'capacity' => 195,
                'year' => 2022,
                'status' => 'active',
            ],
            [
                'model' => 'Airbus A321neo',
                'manufacturer' => 'Airbus',
                'registration' => 'A321EE',
                'capacity' => 235,
                'year' => 2021,
                'status' => 'active',
            ],
            [
                'model' => 'Airbus A321neo',
                'manufacturer' => 'Airbus',
                'registration' => 'A321FF',
                'capacity' => 235,
                'year' => 2022,
                'status' => 'active',
            ],
            [
                'model' => 'Airbus A330-300',
                'manufacturer' => 'Airbus',
                'registration' => 'A330GG',
                'capacity' => 440,
                'year' => 2018,
                'status' => 'active',
            ],
            [
                'model' => 'Airbus A330-300',
                'manufacturer' => 'Airbus',
                'registration' => 'A330HH',
                'capacity' => 440,
                'year' => 2019,
                'status' => 'active',
            ],
            [
                'model' => 'Airbus A350-900',
                'manufacturer' => 'Airbus',
                'registration' => 'A350II',
                'capacity' => 350,
                'year' => 2022,
                'status' => 'active',
            ],
            [
                'model' => 'Airbus A350-900',
                'manufacturer' => 'Airbus',
                'registration' => 'A350JJ',
                'capacity' => 350,
                'year' => 2023,
                'status' => 'active',
            ],
            [
                'model' => 'Airbus A380',
                'manufacturer' => 'Airbus',
                'registration' => 'A380EF',
                'capacity' => 853,
                'year' => 2019,
                'status' => 'inactive',
            ],
            [
                'model' => 'Airbus A380',
                'manufacturer' => 'Airbus',
                'registration' => 'A380FG',
                'capacity' => 853,
                'year' => 2020,
                'status' => 'maintenance',
            ],

            [
                'model' => 'Embraer E190',
                'manufacturer' => 'Embraer',
                'registration' => 'E190IJ',
                'capacity' => 114,
                'year' => 2017,
                'status' => 'active',
            ],
            [
                'model' => 'Embraer E190',
                'manufacturer' => 'Embraer',
                'registration' => 'E190JK',
                'capacity' => 114,
                'year' => 2018,
                'status' => 'active',
            ],
            [
                'model' => 'Embraer E195',
                'manufacturer' => 'Embraer',
                'registration' => 'E195KL',
                'capacity' => 132,
                'year' => 2020,
                'status' => 'active',
            ],
            [
                'model' => 'Embraer E195',
                'manufacturer' => 'Embraer',
                'registration' => 'E195LM',
                'capacity' => 132,
                'year' => 2021,
                'status' => 'active',
            ],
        ];

        foreach($airplanes as $airplane){
            Airplane::create($airplane);
        }
        $this->command->info('Total airplanes: ' . Airplane::count());
        $this->command->info('Active: ' . Airplane::where('status', 'active')->count());
        $this->command->info('Inactive: ' . Airplane::where('status', 'inactive')->count());
        $this->command->info('Maintenance: ' . Airplane::where('status', 'maintenance')->count());
    }
}
