<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function index()
    {
        $flights = [
            [
                'id' => 1,
                'flight_number' => 'SKY101',
                'airline' => 'SkyBooker',
                'origin' => 'JFK',
                'origin_city' => 'New York',
                'destination' => 'LAX',
                'destination_city' => 'Los Angeles',
                'departure_time' => '08:00 AM',
                'arrival_time' => '11:30 AM',
                'duration' => '5h 30m',
                'airplane' => 'Boeing 737-800',
                'price' => 299.00,
                'status' => 'on_time',
                'available_seats' => 45,
            ],
            [
                'id' => 2,
                'flight_number' => 'SKY202',
                'airline' => 'SkyBooker',
                'origin' => 'LHR',
                'origin_city' => 'London',
                'destination' => 'JFK',
                'destination_city' => 'New York',
                'departure_time' => '10:30 AM',
                'arrival_time' => '02:00 PM',
                'duration' => '7h 30m',
                'airplane' => 'Airbus A320neo',
                'price' => 499.00,
                'status' => 'boarding',
                'available_seats' => 28,
            ],
            [
                'id' => 3,
                'flight_number' => 'SKY303',
                'airline' => 'SkyBooker',
                'origin' => 'DXB',
                'origin_city' => 'Dubai',
                'destination' => 'LHR',
                'destination_city' => 'London',
                'departure_time' => '02:45 AM',
                'arrival_time' => '07:15 AM',
                'duration' => '7h 30m',
                'airplane' => 'Boeing 787-9',
                'price' => 599.00,
                'status' => 'departed',
                'available_seats' => 12,
            ],
            [
                'id' => 4,
                'flight_number' => 'SKY404',
                'airline' => 'SkyBooker',
                'origin' => 'CDG',
                'origin_city' => 'Paris',
                'destination' => 'JFK',
                'destination_city' => 'New York',
                'departure_time' => '09:15 AM',
                'arrival_time' => '12:45 PM',
                'duration' => '8h 30m',
                'airplane' => 'Airbus A330-300',
                'price' => 399.00,
                'status' => 'delayed',
                'available_seats' => 56,
            ],
            [
                'id' => 5,
                'flight_number' => 'SKY505',
                'airline' => 'SkyBooker',
                'origin' => 'HND',
                'origin_city' => 'Tokyo',
                'destination' => 'LAX',
                'destination_city' => 'Los Angeles',
                'departure_time' => '05:20 PM',
                'arrival_time' => '10:50 AM',
                'duration' => '10h 30m',
                'airplane' => 'Boeing 777-300ER',
                'price' => 799.00,
                'status' => 'on_time',
                'available_seats' => 89,
            ],
            [
                'id' => 6,
                'flight_number' => 'SKY606',
                'airline' => 'SkyBooker',
                'origin' => 'LAX',
                'origin_city' => 'Los Angeles',
                'destination' => 'JFK',
                'destination_city' => 'New York',
                'departure_time' => '11:00 PM',
                'arrival_time' => '07:30 AM',
                'duration' => '5h 30m',
                'airplane' => 'Boeing 737 MAX 9',
                'price' => 259.00,
                'status' => 'cancelled',
                'available_seats' => 0,
            ],
            [
                'id' => 7,
                'flight_number' => 'SKY707',
                'airline' => 'SkyBooker',
                'origin' => 'JFK',
                'origin_city' => 'New York',
                'destination' => 'LHR',
                'destination_city' => 'London',
                'departure_time' => '06:30 PM',
                'arrival_time' => '06:45 AM',
                'duration' => '7h 15m',
                'airplane' => 'Airbus A380',
                'price' => 549.00,
                'status' => 'on_time',
                'available_seats' => 234,
            ],
            [
                'id' => 8,
                'flight_number' => 'SKY808',
                'airline' => 'SkyBooker',
                'origin' => 'LAX',
                'origin_city' => 'Los Angeles',
                'destination' => 'HND',
                'destination_city' => 'Tokyo',
                'departure_time' => '12:30 PM',
                'arrival_time' => '04:15 PM',
                'duration' => '11h 45m',
                'airplane' => 'Boeing 787-9',
                'price' => 899.00,
                'status' => 'boarding',
                'available_seats' => 67,
            ],
            [
                'id' => 9,
                'flight_number' => 'SKY909',
                'airline' => 'SkyBooker',
                'origin' => 'LHR',
                'origin_city' => 'London',
                'destination' => 'DXB',
                'destination_city' => 'Dubai',
                'departure_time' => '08:45 PM',
                'arrival_time' => '06:30 AM',
                'duration' => '6h 45m',
                'airplane' => 'Airbus A380',
                'price' => 449.00,
                'status' => 'on_time',
                'available_seats' => 156,
            ],
            [
                'id' => 10,
                'flight_number' => 'SKY1010',
                'airline' => 'SkyBooker',
                'origin' => 'CDG',
                'origin_city' => 'Paris',
                'destination' => 'LAX',
                'destination_city' => 'Los Angeles',
                'departure_time' => '11:00 AM',
                'arrival_time' => '02:30 PM',
                'duration' => '10h 30m',
                'airplane' => 'Airbus A330-300',
                'price' => 699.00,
                'status' => 'delayed',
                'available_seats' => 34,
            ],
        ];

        return view('admin.flights', compact('flights'));
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
