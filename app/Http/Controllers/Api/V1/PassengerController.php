<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Passenger\PassengerChangeStatusRequest;
use App\Models\Passenger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Passenger\StorePassengerRequest;
use App\Http\Requests\Passenger\UpdatePassengerRequest;
use App\Http\Resources\Api\V1\PassengerResource;
use App\Http\Resources\Api\V1\PassengerCollection;
use App\Http\Resources\Api\V1\TicketResource;
use App\Services\Admin\PassengerService;
use App\Http\Resources\Api\V1\BookingResource;

class PassengerController extends Controller
{
    public function __construct(private PassengerService $passengerService) {}

    public function index()
    {
        try {
            $result = $this->passengerService->getApiList();

            if (!$result['success']) {
                return Response::error($result['message'], 422);
            }

            return Response::success(
                new PassengerCollection($result['passengers']),
                'Passengers retrieved'
            );

        } catch (\Throwable $e) {
            Log::error('PASSENGER INDEX ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve passengers', 500);
        }
    }

    public function store(StorePassengerRequest $request)
    {
        try {
            $result = $this->passengerService->create($request->validated());

            if (!$result['success']) {
                return Response::error($result['message'], 500);
            }

            return Response::success(
                new PassengerResource($result['passenger']),
                'Passenger created',
                201
            );

        } catch (\Throwable $e) {
            Log::error('PASSENGER STORE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to create passenger', 500);
        }
    }

    public function show(Passenger $passenger)
    {
        try {
            $result = $this->passengerService->getApiShow($passenger);

            if (!$result['success']) {
                return Response::error($result['message'], 500);
            }

            return Response::success(
                $result['passenger'],
                'Passenger retrieved'
            );

        } catch (\Throwable $e) {
            Log::error('PASSENGER SHOW ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve passenger', 500);
        }
    }

    public function update(UpdatePassengerRequest $request, Passenger $passenger)
    {
        try {
            $result = $this->passengerService->update($passenger, $request->validated());

            if (!$result['success']) {
                return Response::error($result['message'], 500);
            }

            return Response::success(
                new PassengerResource($result['passenger']),
                'Passenger updated'
            );

        } catch (\Throwable $e) {
            Log::error('PASSENGER UPDATE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to update passenger', 500);
        }
    }

    public function destroy(Passenger $passenger)
    {
        try {
            $result = $this->passengerService->delete($passenger);

            if (!$result['success']) {
                return Response::error($result['message'], 500);
            }

            return Response::success(null, 'Passenger deleted', 204);

        } catch (\Throwable $e) {
            Log::error('PASSENGER DELETE ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to delete passenger', 500);
        }
    }

    public function ticket(Passenger $passenger)
    {
        try {
            $result = $this->passengerService->getApiTicket($passenger);

            if (!$result['success']) {
                return Response::error($result['message'], 404);
            }

            return Response::success(
                new TicketResource($result['ticket']),
                'Passenger ticket retrieved'
            );

        } catch (\Throwable $e) {
            Log::error('PASSENGER TICKET ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve passenger ticket', 500);
        }
    }
    public function booking(Passenger $passenger)
    {
        try {
            $result = $this->passengerService->getApiBooking($passenger);

            if (!$result['success']) {
                return Response::error($result['message'], 404);
            }

            return Response::success(
                new BookingResource($result['booking']),
            'Passenger booking retrieved'
            );

        } catch (\Throwable $e) {
            Log::error('PASSENGER TICKET ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve passenger booking', 500);
        }
    }

    public function changeStatus(PassengerChangeStatusRequest $request, Passenger $passenger)
    {
        $updated = app(PassengerService::class)->changeStatus(
            $passenger,
            $request->validated()['status']
        );

        if (! $updated) {
            return response()->json([
                'success' => false,
                'message' => "Invalid status transition: {$passenger->status} → {$request->status}"
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Passenger status updated successfully.',
            'passenger' => $updated
        ]);
    }

}
