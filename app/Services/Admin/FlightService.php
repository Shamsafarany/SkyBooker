<?php

namespace App\Services\Admin;

use App\Filters\FlightFilter;
use App\Models\Flight;
use App\Models\Airline;
use App\Models\Airplane;
use App\Models\Passenger;
use App\Models\Ticket;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\Api\V1\FlightResource;
use App\Services\WeatherService;
use App\Http\Resources\Api\V1\FlightCollection;
use Illuminate\Http\Request;

class FlightService
{
    public function getApiList(Request $request)
    {
        $query = Flight::query()
            ->with(['origin', 'destination'])
            ->withCount(['bookings']);
        $query = (new FlightFilter())->apply($query, $request);

        if ($request->filled('sort')) {
            $direction = $request->input('direction', 'asc');
            $query->orderBy($request->sort, $direction);
        }

        $flights = $query->paginate(15);

        return new FlightCollection($flights);

    }


    public function getApiShow(Flight $flight)
    {
        $key = "api.flights.show.{$flight->id}";

        return Cache::remember($key, 60, function () use ($flight) {
            Log::info("FLIGHT SHOW: Cache MISS - querying database for ID {$flight->id}");

            $flight->load([
                'airline',
                'origin',
                'destination',
                'airplane',
                'bookings.passengers.ticket'
            ]);

            return (new FlightResource($flight))->resolve();
        });
    }

    public function create(array $data)
    {
        if ($data['origin_airport_id'] === $data['destination_airport_id']) {
            return [
                'success' => false,
                'message' => 'Origin and destination airports cannot be the same.'
            ];
        }
        $airplane = Airplane::findOrFail($data['airplane_id']);
        if ($airplane->status !== 'active') {
            return [
                'success' => false,
                'message' => 'Selected airplane is not active.'
            ];
        }
        if ($data['total_seats'] > $airplane->capacity) {
            return [
                'success' => false,
                'message' => 'Total seats exceed airplane capacity.'
            ];
        }
        $flight = Flight::create($data);
        return [
            'success' => true,
            'flight' => $flight
        ];
    }

    public function showAdmin(string $id)
    {
        $flight = Flight::with([
            'airline',
            'origin',
            'destination',
            'airplane',
            'bookings.passengers.ticket'
        ])->findOrFail($id);

        $passengers = Passenger::whereHas('booking', function ($query) use ($id) {
            $query->where('flight_id', $id);
        })->with(['ticket'])->paginate(15);

        $weatherService = app(WeatherService::class);
        $originWeather = $weatherService->getWeatherByCity($flight->origin->city);

        return [
            'flight' => $flight,
            'passengers' => $passengers,
            'originWeather' => $originWeather,
        ];
    }

    public function update(Flight $flight, array $data)
    {
        $flight->update($data);
        $flight->load(['airline', 'origin', 'destination']);

        return [
            'success' => true,
            'flight' => $flight
        ];
    }

    public function delete(Flight $flight)
    {
        if ($flight->bookings()->exists()) {
            Log::warning('Flight deletion blocked - has bookings', [
                'flight_id' => $flight->id,
                'flight_number' => $flight->number,
                'bookings_count' => $flight->bookings()->count(),
            ]);
            return [
                'success' => false,
                'message' => 'Cannot delete flight with existing bookings.'
            ];
        }
        $flight->delete();
        return [
            'success' => true
        ];
    }

    public function getAllWithStats()
    {
        $flights = Flight::with([
            'airline',
            'origin',
            'destination',
            'airplane',
        ])->orderBy('departure_date')->get();

        $airlines = Airline::with(['flights' => function ($query) {
            $query->with([
                'origin',
                'destination',
                'airplane',
            ])->orderBy('departure_date');
        }])->has('flights')->get();    

        $stats = Cache::remember('admin.flights.stats', 60, function () use ($flights, $airlines) {
            Log::channel('booking')->info('ADMIN FLIGHT STATS: Cache MISS');

            return [
                'total' => $flights->count(),
                'total_airlines' => $airlines->count(),
                'open' => $flights->where('status', 'open')->count(),
                'closing' => $flights->where('status', 'closing')->count(),
                'completed' => $flights->where('status', 'completed')->count(),
                'revenue' => $flights->sum('price'),
            ];
        });
        Log::channel('booking')->info('ADMIN FLIGHT STATS: Cache HIT');
        return compact(['flights', 'stats', 'airlines']);
    }

    public function getApiBookings(Flight $flight){
        $bookings = $flight->bookings()->paginate(15);
        Log::info("FLIGHT BOOKINGS: Retrieved " . $bookings->total() . " bookings");

        return [
            'success' => true,
            'bookings' => $bookings
        ];
    }

    public function getApiTickets(Flight $flight){
        $tickets = Ticket::whereHas('passenger.booking', function ($query) use ($flight) {
                $query->where('flight_id', $flight->id);
            })
            ->with(['passenger'])
            ->paginate(15);
        Log::info("FLIGHT TICKETS: Retrieved " . $tickets->total() . " tickets");

        return [
            'success' => true,
            'tickets' => $tickets
        ];
    }
}
