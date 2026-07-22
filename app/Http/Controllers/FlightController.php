<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FlightController extends Controller
{

    private function getFlights()
    {
        return [
            1 => [
                'id' => 1,
                'flight_number' => 'SKY101',
                'airline' => 'SkyBooker',
                'origin' => 'JFK',
                'origin_city' => 'New York',
                'destination' => 'LAX',
                'destination_city' => 'Los Angeles',
                'departure_date' => '2026-08-15',
                'departure_time' => '08:00 AM',
                'arrival_time' => '11:30 AM',
                'duration' => '5h 30m',
                'airplane' => 'Boeing 737-800',
                'price' => 299.00,
                'status' => 'scheduled',
                'total_seats' => 180,
                'booked_seats' => 45,
                'available_seats' => 135,
                'booking_deadline' => '2026-08-14 23:59',
                'passengers' => [],
                'created_at' => '2026-08-01 10:00:00',
                'updated_at' => '2026-08-10 14:30:00',
            ],
            2 => [
                'id' => 2,
                'flight_number' => 'SKY202',
                'airline' => 'SkyBooker',
                'origin' => 'LHR',
                'origin_city' => 'London',
                'destination' => 'JFK',
                'destination_city' => 'New York',
                'departure_date' => '2026-08-20',
                'departure_time' => '10:30 AM',
                'arrival_time' => '02:00 PM',
                'duration' => '7h 30m',
                'airplane' => 'Airbus A320neo',
                'price' => 499.00,
                'status' => 'open',
                'total_seats' => 195,
                'booked_seats' => 120,
                'available_seats' => 75,
                'booking_deadline' => '2026-08-19 23:59',
                'passengers' => [],
                'created_at' => '2026-08-02 09:00:00',
                'updated_at' => '2026-08-12 16:00:00',
            ],
            3 => [
                'id' => 3,
                'flight_number' => 'SKY303',
                'airline' => 'SkyBooker',
                'origin' => 'DXB',
                'origin_city' => 'Dubai',
                'destination' => 'LHR',
                'destination_city' => 'London',
                'departure_date' => '2026-09-01',
                'departure_time' => '02:45 AM',
                'arrival_time' => '07:15 AM',
                'duration' => '7h 30m',
                'airplane' => 'Boeing 787-9',
                'price' => 599.00,
                'status' => 'closing',
                'total_seats' => 330,
                'booked_seats' => 280,
                'available_seats' => 50,
                'booking_deadline' => '2026-08-31 23:59',
                'passengers' => [],
                'created_at' => '2026-08-03 08:00:00',
                'updated_at' => '2026-08-13 10:00:00',
            ],
            4 => [
                'id' => 4,
                'flight_number' => 'SKY404',
                'airline' => 'SkyBooker',
                'origin' => 'CDG',
                'origin_city' => 'Paris',
                'destination' => 'JFK',
                'destination_city' => 'New York',
                'departure_date' => '2026-07-25',
                'departure_time' => '09:15 AM',
                'arrival_time' => '12:45 PM',
                'duration' => '8h 30m',
                'airplane' => 'Airbus A330-300',
                'price' => 399.00,
                'status' => 'completed',
                'total_seats' => 440,
                'booked_seats' => 440,
                'available_seats' => 0,
                'booking_deadline' => '2026-07-24 23:59',
                'passengers' => [],
                'created_at' => '2026-07-01 12:00:00',
                'updated_at' => '2026-07-24 20:00:00',
            ],
            5 => [
                'id' => 5,
                'flight_number' => 'SKY505',
                'airline' => 'SkyBooker',
                'origin' => 'HND',
                'origin_city' => 'Tokyo',
                'destination' => 'LAX',
                'destination_city' => 'Los Angeles',
                'departure_date' => '2026-10-10',
                'departure_time' => '05:20 PM',
                'arrival_time' => '10:50 AM',
                'duration' => '10h 30m',
                'airplane' => 'Boeing 777-300ER',
                'price' => 799.00,
                'status' => 'scheduled',
                'total_seats' => 396,
                'booked_seats' => 12,
                'available_seats' => 384,
                'booking_deadline' => '2026-10-09 23:59',
                'passengers' => [],
                'created_at' => '2026-08-04 11:00:00',
                'updated_at' => '2026-08-14 09:00:00',
            ],
            6 => [
                'id' => 6,
                'flight_number' => 'SKY606',
                'airline' => 'SkyBooker',
                'origin' => 'LAX',
                'origin_city' => 'Los Angeles',
                'destination' => 'JFK',
                'destination_city' => 'New York',
                'departure_date' => '2026-07-30',
                'departure_time' => '11:00 PM',
                'arrival_time' => '07:30 AM',
                'duration' => '5h 30m',
                'airplane' => 'Boeing 737 MAX 9',
                'price' => 259.00,
                'status' => 'cancelled',
                'total_seats' => 220,
                'booked_seats' => 0,
                'available_seats' => 220,
                'booking_deadline' => null,
                'passengers' => [],
                'created_at' => '2026-07-20 15:00:00',
                'updated_at' => '2026-07-29 18:00:00',
            ],
            7 => [
                'id' => 7,
                'flight_number' => 'SKY707',
                'airline' => 'SkyBooker',
                'origin' => 'JFK',
                'origin_city' => 'New York',
                'destination' => 'LHR',
                'destination_city' => 'London',
                'departure_date' => '2026-08-25',
                'departure_time' => '06:30 PM',
                'arrival_time' => '06:45 AM',
                'duration' => '7h 15m',
                'airplane' => 'Airbus A380',
                'price' => 549.00,
                'status' => 'open',
                'total_seats' => 500,
                'booked_seats' => 310,
                'available_seats' => 190,
                'booking_deadline' => '2026-08-24 23:59',
                'passengers' => [],
                'created_at' => '2026-08-05 13:00:00',
                'updated_at' => '2026-08-15 11:00:00',
            ],
            8 => [
                'id' => 8,
                'flight_number' => 'SKY808',
                'airline' => 'SkyBooker',
                'origin' => 'LAX',
                'origin_city' => 'Los Angeles',
                'destination' => 'HND',
                'destination_city' => 'Tokyo',
                'departure_date' => '2026-09-15',
                'departure_time' => '12:30 PM',
                'arrival_time' => '04:15 PM',
                'duration' => '11h 45m',
                'airplane' => 'Boeing 787-9',
                'price' => 899.00,
                'status' => 'open',
                'total_seats' => 330,
                'booked_seats' => 200,
                'available_seats' => 130,
                'booking_deadline' => '2026-09-14 23:59',
                'passengers' => [],
                'created_at' => '2026-08-06 14:00:00',
                'updated_at' => '2026-08-16 12:00:00',
            ],
            9 => [
                'id' => 9,
                'flight_number' => 'SKY909',
                'airline' => 'SkyBooker',
                'origin' => 'LHR',
                'origin_city' => 'London',
                'destination' => 'DXB',
                'destination_city' => 'Dubai',
                'departure_date' => '2026-06-20',
                'departure_time' => '08:45 PM',
                'arrival_time' => '06:30 AM',
                'duration' => '6h 45m',
                'airplane' => 'Airbus A380',
                'price' => 449.00,
                'status' => 'completed',
                'total_seats' => 500,
                'booked_seats' => 480,
                'available_seats' => 20,
                'booking_deadline' => '2026-06-19 23:59',
                'passengers' => [],
                'created_at' => '2026-06-01 10:00:00',
                'updated_at' => '2026-06-19 22:00:00',
            ],
            10 => [
                'id' => 10,
                'flight_number' => 'SKY1010',
                'airline' => 'SkyBooker',
                'origin' => 'CDG',
                'origin_city' => 'Paris',
                'destination' => 'LAX',
                'destination_city' => 'Los Angeles',
                'departure_date' => '2026-11-01',
                'departure_time' => '11:00 AM',
                'arrival_time' => '02:30 PM',
                'duration' => '10h 30m',
                'airplane' => 'Airbus A330-300',
                'price' => 699.00,
                'status' => 'scheduled',
                'total_seats' => 440,
                'booked_seats' => 30,
                'available_seats' => 410,
                'booking_deadline' => '2026-10-31 23:59',
                'passengers' => [],
                'created_at' => '2026-08-07 09:00:00',
                'updated_at' => '2026-08-17 08:00:00',
            ],
        ];
    }
    public function index(){
    $flights = array_values($this->getFlights());

    return view('admin.flights.index', compact('flights'));

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
        $flights = $this->getFlights();
        if(!isset($flights[$id])){
            abort(404, 'Flight not found');
        }
        $flight = $flights[$id];
        return view('admin.flights.show', compact('flight'));
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
