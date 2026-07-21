<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function index()
    {
        // Fake airport data
        $airports = [
            [
                'id' => 1,
                'name' => 'John F. Kennedy International',
                'code' => 'JFK',
                'city' => 'New York',
                'country' => 'USA',
                'terminals' => 6,
                'status' => 'active',
            ],
            [
                'id' => 2,
                'name' => 'Los Angeles International',
                'code' => 'LAX',
                'city' => 'Los Angeles',
                'country' => 'USA',
                'terminals' => 9,
                'status' => 'active',
            ],
            [
                'id' => 3,
                'name' => 'Heathrow Airport',
                'code' => 'LHR',
                'city' => 'London',
                'country' => 'UK',
                'terminals' => 5,
                'status' => 'active',
            ],
            [
                'id' => 4,
                'name' => 'Dubai International',
                'code' => 'DXB',
                'city' => 'Dubai',
                'country' => 'UAE',
                'terminals' => 3,
                'status' => 'active',
            ],
            [
                'id' => 5,
                'name' => 'Tokyo Haneda',
                'code' => 'HND',
                'city' => 'Tokyo',
                'country' => 'Japan',
                'terminals' => 3,
                'status' => 'inactive',
            ],
            [
                'id' => 6,
                'name' => 'Paris Charles de Gaulle',
                'code' => 'CDG',
                'city' => 'Paris',
                'country' => 'France',
                'terminals' => 4,
                'status' => 'active',
            ],
        ];

        return view('admin.airports', compact('airports'));
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
