<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AirplaneController extends Controller
{
    public function index()
    {
        $airplanes = [
            [
                'id' => 1,
                'model' => 'Boeing 737-800',
                'manufacturer' => 'Boeing',
                'registration' => 'N737AA',
                'capacity' => 189,
                'year' => 2018,
                'status' => 'active',
                'image' => null,
            ],
            [
                'id' => 2,
                'model' => 'Airbus A320neo',
                'manufacturer' => 'Airbus',
                'registration' => 'A320BB',
                'capacity' => 195,
                'year' => 2020,
                'status' => 'active',
                'image' => null,
            ],
            [
                'id' => 3,
                'model' => 'Boeing 787-9 Dreamliner',
                'manufacturer' => 'Boeing',
                'registration' => 'N787DC',
                'capacity' => 330,
                'year' => 2022,
                'status' => 'maintenance',
                'image' => null,
            ],
            [
                'id' => 4,
                'model' => 'Airbus A380',
                'manufacturer' => 'Airbus',
                'registration' => 'A380EF',
                'capacity' => 853,
                'year' => 2019,
                'status' => 'inactive',
                'image' => null,
            ],
            [
                'id' => 5,
                'model' => 'Boeing 777-300ER',
                'manufacturer' => 'Boeing',
                'registration' => 'N777GH',
                'capacity' => 396,
                'year' => 2021,
                'status' => 'active',
                'image' => null,
            ],
            [
                'id' => 6,
                'model' => 'Embraer E190',
                'manufacturer' => 'Embraer',
                'registration' => 'E190IJ',
                'capacity' => 114,
                'year' => 2017,
                'status' => 'active',
                'image' => null,
            ],
            [
                'id' => 7,
                'model' => 'Airbus A330-300',
                'manufacturer' => 'Airbus',
                'registration' => 'A330KL',
                'capacity' => 440,
                'year' => 2016,
                'status' => 'maintenance',
                'image' => null,
            ],
            [
                'id' => 8,
                'model' => 'Boeing 737 MAX 9',
                'manufacturer' => 'Boeing',
                'registration' => 'N737MX',
                'capacity' => 220,
                'year' => 2023,
                'status' => 'active',
                'image' => null,
            ],
        ];

        return view('admin.airplanes', compact('airplanes'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }
    public function destroy(string $id)
    {
        //
    }
}
