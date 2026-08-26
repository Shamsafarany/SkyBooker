<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Ticket;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Flight\StoreFlightRequest;
use App\Http\Requests\Flight\UpdateFlightRequest;
use App\Http\Resources\Api\V1\FlightResource;
use App\Http\Resources\Api\V1\FlightCollection;
use App\Http\Resources\Api\V1\BookingCollection;
use App\Http\Resources\Api\V1\TicketCollection;

class FlightController extends Controller
{
    public function index()
    {
        try {
            $flights = Flight::with(['airline','origin','destination','airplane'])
                ->paginate(15);
            Log::info('FLIGHT INDEX: Cache MISS - querying database');    

            return Response::success(new FlightCollection($flights), 'Flights retrieved');

        } catch (\Throwable $e) {
            Log::error('FLIGHT INDEX ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve flights', 500);
        }
    }

    public function store(StoreFlightRequest $request)
    {
        try {
            $flight = Flight::create($request->validated());

            return Response::success(new FlightResource($flight), 'Flight created', 201);

        } catch (\Throwable $e) {
            Log::error('FLIGHT STORE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to create flight', 500);
        }
    }

    public function show(Flight $flight)
    {
        try {
            $key = "api.flights.show.{$flight->id}";

            $data = Cache::remember($key, 60, function () use ($flight) {
                Log::info('FLIGHT SHOW: Cache MISS - querying database');

                $flight->load(['airline','origin','destination','airplane']);

                return (new FlightResource($flight))->resolve();
            });

            Log::info('FLIGHT SHOW: Cache HIT - getting cache');

            return Response::success($data, 'Flight retrieved');

        } catch (\Throwable $e) {
            Log::error('FLIGHT SHOW ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve flight', 500);
        }
    }

    public function update(UpdateFlightRequest $request, Flight $flight)
    {
        try {
            $flight->update($request->validated());

            return Response::success(new FlightResource($flight), 'Flight updated');

        } catch (\Throwable $e) {
            Log::error('FLIGHT UPDATE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to update flight', 500);
        }
    }

    public function destroy(Flight $flight)
    {
        try {
            $flight->delete();

            return Response::success(null, 'Flight deleted', 204);

        } catch (\Throwable $e) {
            Log::error('FLIGHT DELETE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to delete flight', 500);
        }
    }

    public function bookings(Flight $flight)
    {
        try {
            $bookings = $flight->bookings()->paginate(15);

            return Response::success(new BookingCollection($bookings), 'Flight bookings retrieved');

        } catch (\Throwable $e) {
            Log::error('FLIGHT BOOKINGS ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve flight bookings', 500);
        }
    }

    public function tickets(Flight $flight)
    {
        try {
            $tickets = Ticket::whereHas('passenger.booking', function ($query) use ($flight) {
                $query->where('flight_id', $flight->id);
            })
            ->with(['passenger'])
            ->paginate(15);

            return Response::success(new TicketCollection($tickets), 'Flight tickets retrieved');

        } catch (\Throwable $e) {
            Log::error('FLIGHT TICKETS ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve flight tickets', 500);
        }
    }
}
