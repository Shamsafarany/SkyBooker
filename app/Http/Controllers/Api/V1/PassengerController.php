<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Passenger;
use App\Models\Ticket;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Passenger\StorePassengerRequest;
use App\Http\Requests\Passenger\UpdatePassengerRequest;
use App\Http\Resources\Api\V1\PassengerResource;
use App\Http\Resources\Api\V1\PassengerCollection;
use App\Http\Resources\Api\V1\TicketResource;

class PassengerController extends Controller
{
    public function index()
    {
        try {
            $passengers = Passenger::with(['booking', 'ticket'])
                ->paginate(15);
            Log::info('PASSENGER INDEX: Cache MISS - querying database');  
            return Response::success(new PassengerCollection($passengers), 'Passengers retrieved');

        } catch (\Throwable $e) {
            Log::error('PASSENGER INDEX ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve passengers', 500);
        }
    }

    public function store(StorePassengerRequest $request)
    {
        try {
            $passenger = Passenger::create($request->validated());
            $passenger->load(['booking', 'ticket']);

            return Response::success(new PassengerResource($passenger), 'Passenger created', 201);

        } catch (\Throwable $e) {
            Log::error('PASSENGER STORE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to create passenger', 500);
        }
    }

    public function show(Passenger $passenger)
    {
        try {
            $key = "api.passengers.show.{$passenger->id}";

            $data = Cache::remember($key, 60, function () use ($passenger) {
                Log::info('PASSENGER SHOW: Cache MISS - querying database');

                $passenger->load(['booking', 'ticket']);

                return (new PassengerResource($passenger))->resolve();
            });

            Log::info('PASSENGER SHOW: Cache HIT - getting cache');

            return Response::success($data, 'Passenger retrieved');

        } catch (\Throwable $e) {
            Log::error('PASSENGER SHOW ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve passenger', 500);
        }
    }

    public function update(UpdatePassengerRequest $request, Passenger $passenger)
    {
        try {
            $passenger->update($request->validated());
            $passenger->load(['booking', 'ticket']);

            Cache::forget("api.passengers.show.{$passenger->id}");

            return Response::success(new PassengerResource($passenger), 'Passenger updated');

        } catch (\Throwable $e) {
            Log::error('PASSENGER UPDATE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to update passenger', 500);
        }
    }

    public function destroy(Passenger $passenger)
    {
        try {
            $passenger->delete();
            Cache::forget("api.passengers.show.{$passenger->id}");

            return Response::success(null, 'Passenger deleted', 204);

        } catch (\Throwable $e) {
            Log::error('PASSENGER DELETE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to delete passenger', 500);
        }
    }

    public function ticket(Passenger $passenger)
    {
        try {
            $ticket = $passenger->ticket()->with(['passenger'])->first();

            if (!$ticket) {
                return Response::error('No ticket found for this passenger', 404);
            }

            return Response::success(new TicketResource($ticket), 'Passenger ticket retrieved');

        } catch (\Throwable $e) {
            Log::error('PASSENGER TICKET ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve passenger ticket', 500);
        }
    }
}
