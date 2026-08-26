<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FlightCollection;
use App\Http\Resources\Api\V1\BookingCollection;
use App\Http\Resources\Api\V1\TicketCollection;
use App\Models\Flight;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\FlightResource;
use App\Http\Requests\Flight\StoreFlightRequest;
use App\Http\Requests\Flight\UpdateFlightRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class FlightController extends Controller
{

    public function index()
    {
        $flights = Flight::with(['airline','origin','destination','airplane'])
            ->paginate(15);
        return new FlightCollection($flights);
    }


    public function store(StoreFlightRequest $request)
    {
        Cache::forget('api.flights.list');
        return response()->json(
            new FlightResource(Flight::create($request->validated())),
            201
        );
    }

    public function show(Flight $flight)
    {
        $key = "api.flights.{$flight->id}";

        $data = Cache::remember($key, 60, function () use ($flight) {
            $flight->load([
                'airline',
                'origin',
                'destination',
                'airplane'
            ]);
            Log::info('Flight INDEX: Cache MISS - querying database');
            return (new FlightResource($flight))->resolve();
        });
        Log::info('Flight INDEX: Cache HIT - getting cache');
        return Response::success($data, 'Flight retrieved');
    }


    public function update(UpdateFlightRequest $request, Flight $flight)
    {
        $flight->update($request->validated());
        Cache::forget('api.flights.list');
        return new FlightResource($flight);
    }

    public function destroy(Flight $flight)
    {
        $flight->delete();
        Cache::forget('api.flights.list');
        return response()->json(null, 204);
    }

    public function bookings(Flight $flight)
    {
        //Eager load relationships
        $bookings = $flight->bookings()
            ->paginate(15);
        
        return new BookingCollection($bookings);
    }
    public function tickets(Flight $flight)
    {
        // Get all tickets through bookings → passengers
        $tickets = Ticket::whereHas('passenger.booking', function ($query) use ($flight) {
            $query->where('flight_id', $flight->id);
        })->with(['passenger'])->paginate(15);
        
        return new TicketCollection($tickets);
    }
}
