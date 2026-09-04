<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\FlightUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Flight\FlightChangeStatusRequest;
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
use App\Services\Admin\FlightService;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function __construct(private FlightService $flightService) {}
    public function index(Request $request)
    {
        try {
            $flights = $this->flightService->getApiList($request); 
            return Response::success($flights, 'Flights retrieved');

        } catch (\Throwable $e) {
            Log::error('FLIGHT INDEX ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve flights', 500);
        }
    }

    public function store(StoreFlightRequest $request)
    {
        try {
            $result = $this->flightService->create($request->validated());
            if (!$result['success']) {
                return Response::error($result['message'], 422);
            }
            return Response::success(new FlightResource($result['flight']), 'Flight created', 201);

        } catch (\Throwable $e) {
            Log::error('FLIGHT STORE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to create flight', 500);
        }
    }

    public function show(Flight $flight)
    {
        try {
            $data = $this->flightService->getApiShow($flight);
            Log::info("FLIGHT SHOW: Cache HIT for ID {$flight->id}");
            return Response::success($data, 'Flight retrieved');
        } catch (\Throwable $e) {
            Log::error('FLIGHT SHOW ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve flight', 500);
        }
    }

    public function update(UpdateFlightRequest $request, Flight $flight)
    {
        try {
            $result = $this->flightService->update($flight, $request->validated());
            if (!$result['success']) {
                return Response::error($result['message'], 400);
            }
            event(new FlightUpdated($flight));
            return Response::success(new FlightResource($result['flight']), 'Flight updated');

        } catch (\Throwable $e) {
            Log::error('FLIGHT UPDATE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to update flight', 500);
        }
    }

    public function destroy(Flight $flight)
    {
        try {
            $result = $this->flightService->delete($flight);
            if (!$result['success']) {
                return Response::error($result['message'], 400);
            }

            return Response::success(null, 'Flight deleted', 204);

        } catch (\Throwable $e) {
            Log::error('FLIGHT DELETE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to delete flight', 500);
        }
    }

    public function bookings(Flight $flight)
    {
        try {
            $result= $this->flightService->getApiBookings($flight);

            return Response::success(new BookingCollection($result['bookings']), 'Flight bookings retrieved');

        } catch (\Throwable $e) {
            Log::error('FLIGHT BOOKINGS ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve flight bookings', 500);
        }
    }

    public function tickets(Flight $flight)
    {
        try {
            $result = $this->flightService->getApiTickets($flight);
            return Response::success(new TicketCollection($result['tickets']), 'Flight tickets retrieved');

        } catch (\Throwable $e) {
            Log::error('FLIGHT TICKETS ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve flight tickets', 500);
        }
    }

    public function changeStatus(FlightChangeStatusRequest $request, Flight $flight)
    {
        $updated = app(FlightService::class)->changeStatus(
            $flight,
            $request->validated()['status']
        );

        if (! $updated) {
            return Response::error(
                "Invalid status transition: {$flight->status} → {$request->status}"
            );
        }

        return Response::success("Flight status updated successfully.", [
            'flight' => $updated
        ]);
    }

}
